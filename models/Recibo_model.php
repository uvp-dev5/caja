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
                    pe.TAX_ID, pago.RECEIPT_NUMBER, 
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
}