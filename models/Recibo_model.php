<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recibo_model extends CI_Model {

    private $janus;

    public function __construct() {
        parent::__construct();
        $this->janus = $this->load->database('janus', TRUE);
    }

    public function getInfoByComprador($comprador_id) {
        $sql = "SELECT t1.CHARGE_CREDIT_CODE, t2.LONG_DESC cargo, t1.REVISION_OPID opid
        FROM CHARGECREDIT t1, CODE_CHARGECREDIT t2
        WHERE CHARGECREDITNUMBER = '$comprador_id'
        AND t1.CHARGE_CREDIT_CODE = t2.CODE_VALUE_KEY ";

        $query = $this->campus->query($sql);

        return $query->row();
    }

    public function getInfoById($recibo_id) {
        $sql = "SELECT 
                    pe.PEOPLE_ID MATRICULA, 
                    pe.TAX_ID, 
                    pago.RECEIPT_NUMBER, 
                    pe.LAST_NAME + ' ' + pe.FIRST_NAME + ' ' + pe.MIDDLE_NAME ALUMNO, 
                    tblPer.LONG_DESC + ' ' + pago.ACADEMIC_YEAR PERIODOLECTIVO, 
                    ( SELECT LONG_DESC 
                        FROM CODE_CLASSLEVEL 
                        WHERE CODE_VALUE_KEY = ( 
                            SELECT MAX( acaTemp.CLASS_LEVEL)
                                FROM ACADEMIC acaTemp
                                WHERE  acaTemp.PEOPLE_ID = recibo.PEOPLE_org_ID
                                    AND TRANSCRIPT_SEQ = (
                                        SELECT MAX(acadtem.TRANSCRIPT_SEQ) 
                                            FROM ACADEMIC acadtem 
                                            WHERE acadtem.PEOPLE_ID = acaTemp.PEOPLE_ID 
                                        ) 
                            )
                    ) SEMESTRE, 
                    ( SELECT DISTINCT top(1) cocu.CODE_VALUE 
                        FROM CODE_CURRICULUM cocu, ACADEMIC aca 
                        WHERE aca.CURRICULUM = cocu.CODE_VALUE_KEY 
                        AND aca.PEOPLE_ID = recibo.PEOPLE_ORG_ID
                        AND TRANSCRIPT_SEQ = (
                            SELECT MAX(acadtem.TRANSCRIPT_SEQ) 
                                FROM ACADEMIC acadtem 
                                WHERE acadtem.PEOPLE_ID = aca.PEOPLE_ID 
                            )
                    ) CARRERA, 
                    tblPag.LONG_DESC DESCRIPCION, 
                    CONVERT(decimal(10,2),pago.AMOUNT) IMPORTEPAGO, 
                    CONVERT(decimal(10,2),pago.PAID_AMOUNT) IMPORTEAPLICADO, 
                    CONVERT(decimal(10,2),pago.BALANCE_AMOUNT) SALDOAFAVOR, 
                    CONVERT(VARCHAR,pago.CREATE_DATE,3) PAGADOELDIA, 
                    dbo.fnConvierteImporte (pago.RECEIPT_NUMBER) CANTIDADCONLETRAS, 
                    recibo.RECEIPT_PAYMENT_TYPE FormaPago, 
                    ( SELECT COUNT(aplPgo.ChargeAppliedTo) ConteoRegistros
                        FROM ChargeCreditApplication aplPgo, CHARGECREDIT cargo, CHARGECREDIT recibo
                        WHERE cargo.CHARGECREDITNUMBER = aplPgo.ChargeAppliedTo 
                            AND aplPgo.ChargeCreditSource = recibo.CHARGECREDITNUMBER
                            AND recibo.RECEIPT_NUMBER = '$recibo_id'
                    ) conteoRegistros 
                FROM 
                    CODE_ACATERM tblPer, 
                    CODE_CHARGECREDIT tblPag, 
                    CHARGECREDIT pago, 
                    CASHRECEIPT recibo, PEOPLE pe
                WHERE recibo.RECEIPT_NUMBER = pago.RECEIPT_NUMBER
                    AND tblPer.CODE_VALUE_KEY = pago.ACADEMIC_TERM
                    AND tblPag.CODE_VALUE_KEY = pago.CHARGE_CREDIT_CODE
                    AND pe.PEOPLE_CODE_ID = pago.PEOPLE_ORG_CODE_ID 
                    AND recibo.RECEIPT_NUMBER = '$recibo_id' ";
        $query = $this->campus->query($sql);

        return $query->row();
    }

    public function getCorte($plantel, $fecha, $cajero, $cancelado = false) {
        $this->janus->select_max('no_recibo', 'maximo');
        $this->janus->select_min('no_recibo', 'minimo');
        $this->janus->select_sum('importe', 'total');
        if (! $cancelado ) {
            $this->janus->where(array(
                'unidad' => $plantel,
                'fecha_opera' => $fecha,
                'realizo' => $cajero,
                'cancelado' => '0'
            ));
        } else {
            $this->janus->where(array(
                'unidad' => $plantel,
                'fecha_opera' => $fecha,
                'realizo' => $cajero,
                'cancelado' => $cancelado
            ));
        }
        
        $query = $this->get('caja_recibos');

        return $query->row();
    }

    public function listByPlantelAndDate($plantel, $fecha, $cancelado = false) {
        $this->janus->select('no_recibo, matricula, descri, importe, DATE_FORMAT(fecha_opera,"%d/%m/%Y") AS fecha_opera, tipo_pago');
        if (! $cancelado ) {
            $this->janus->where(array(
                'unidad' => $plantel,
                'fecha_opera' => $fecha,
                'cancelado' => '0'
            ));
        } else {
            $this->janus->where(array(
                'unidad' => $plantel,
                'fecha_opera' => $fecha,
                'cancelado' => $cancelado
            ));
        }
        $query = $this->janus->get('caja_recibos');

        return $query->result();
    }

    public function listByComprador($comprador_id) {
        $sql = "SELECT DISTINCT
            t1.PEOPLE_ORG_ID pid, 
            t3.TAX_ID taxid,
            (t3.FIRST_NAME+' '+t3.MIDDLE_NAME+' '+t3.LAST_NAME) nombre, 
            t1.RECEIPT_NUMBER recibo, 
            t1.RECEIPT_AMOUNT monto,
            t2.CHARGECREDITNUMBER pago,
            t4.ChargeAppliedTo aplicado,
            Convert(NVARCHAR,t1.ENTRY_DATE,103) fecha
            FROM CASHRECEIPT t1, CHARGECREDIT t2, PEOPLE t3, ChargeCreditApplication t4
            WHERE t1.RECEIPT_NUMBER = t2.RECEIPT_NUMBER
                AND t1.PEOPLE_ORG_ID = t3.PEOPLE_ID
                AND t1.PEOPLE_ORG_ID = '$comprador_id'
                AND t2.CHARGECREDITNUMBER = t4.ChargeCreditSource
            ORDER BY recibo DESC";
        
        $query = $this->campus->query($sql);

        return $query->result();
    }

    public function listByLote($lote_id) {
        $sql = "SELECT 
            pe.PEOPLE_ID MATRICULA, 
            recibo.CREATE_OPID, 
            pe.TAX_ID, 
            pago.CRG_CRD_DESC, 
            pago.REVERSED, 
            pago.VOID_FLAG, 
            pago.CHARGE_CREDIT_CODE, 
            pago.ACADEMIC_SESSION, 
            pe.LAST_NAME + ' ' + pe.FIRST_NAME + ' ' + pe.MIDDLE_NAME ALUMNO, 
            tblPer.LONG_DESC + ' ' + pago.ACADEMIC_YEAR PERIODOLECTIVO,
            OFFICE, 
            recibo.ACADEMIC_SESSION,
            recibo.ACADEMIC_YEAR,
            recibo.ACADEMIC_TERM, 
            recibo.RECEIPT_NUMBER, 
            tblPag.LONG_DESC DESCRIPCION, 
            CONVERT(decimal(10,2),pago.AMOUNT) IMPORTEPAGO, 
            CONVERT(decimal(10,2),pago.PAID_AMOUNT) IMPORTEAPLICADO, 
            CONVERT(decimal(10,2),pago.BALANCE_AMOUNT) SALDOAFAVOR, 
            ( SELECT DISTINCT top(1) cocu.LONG_DESC 
                FROM CODE_CURRICULUM cocu, ACADEMIC aca 
                WHERE aca.CURRICULUM = cocu.CODE_VALUE_KEY 
                    AND aca.PEOPLE_ID = recibo.PEOPLE_ORG_ID
                    AND TRANSCRIPT_SEQ = (
                        SELECT MAX(acadtem.TRANSCRIPT_SEQ) 
                            FROM ACADEMIC acadtem 
                            WHERE acadtem.PEOPLE_ID = aca.PEOPLE_ID 
                    )
            ) CARRERA, 
            CONVERT(VARCHAR,pago.CREATE_DATE,3) PAGADOELDIA, 
            recibo.RECEIPT_PAYMENT_TYPE FormaPago
        FROM   
            CODE_ACATERM tblPer, 
            CODE_CHARGECREDIT tblPag, 
            CHARGECREDIT pago, 
            CASHRECEIPT recibo, 
            PEOPLE pe
        WHERE 
            recibo.RECEIPT_NUMBER = pago.RECEIPT_NUMBER
            AND tblPer.CODE_VALUE_KEY = pago.ACADEMIC_TERM
            AND tblPag.CODE_VALUE_KEY = pago.CHARGE_CREDIT_CODE
            AND pe.PEOPLE_CODE_ID = pago.PEOPLE_ORG_CODE_ID 
            AND recibo.BATCH = '$lote_id' ";

        $query = $this->campus->query($sql);

        return $query->result();
    }
}