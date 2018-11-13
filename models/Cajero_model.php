<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cajero_model extends CI_Model {

    private $campus;

    public function __construct() {
        parent::__construct();
        $this->campus = $this->load->database('campus', TRUE);
    }

    public function getLotesPerDay($cajero, $plantel, $fecha_inicial, $fecha_final = false) {
        $sql = "SELECT 
                    b.CREATE_OPID,
                    a.PEOPLE_CODE_ID,
                    b.ACADEMIC_TERM,
                    c.MEDIUM_DESC,
                    b.ACADEMIC_SESSION,
                    b.ACADEMIC_YEAR,
                    Convert(NVARCHAR,b.CREATE_DATE,103) PAYMENT_DAY,
                    b.BATCH_NUMBER,
                    b.ITEMS_ENTERED 
                FROM 
                    BATCHHEADER b LEFT JOIN CODE_ACATERM c ON b.ACADEMIC_TERM = c.CODE_VALUE_KEY 
                    LEFT JOIN ABT_USERS a ON b.CREATE_OPID = a.OPERATOR_ID ";
        if ( $fecha_final ) {
            $sql.= "WHERE 
                        BATCH_DATE BETWEEN '$fecha' AND '$fecha_final' 
                        AND b.CREATE_OPID = '$cajero' ";
        } else {
            $sql.= "WHERE 
                        BATCH_DATE = '$fecha'
                        AND b.CREATE_OPID = '$cajero' ";
        }

        if ( $plantel ) {
            $sql.= "AND b.ACADEMIC_SESSION = '$plantel' ";
        }
        $sql.= "AND ITEMS_ENTERED > 0 
                ORDER BY b.CREATE_OPID";

        $query = $this->campus->query($sql);

        return $sql;
    }

    public function getVouchersPerDay($plantel, $fecha_inicial, $fecha_final) {
        $sql = "SELECT 
                    RECEIPT_NUMBER recibo, 
                    c.PEOPLE_ORG_ID idpc,
                    TAX_ID matricula, 
                    (p.FIRST_NAME + ' ' + p.MIDDLE_NAME + ' ' + p.LAST_NAME) alumno, 
                    BATCH lote, 
                    Convert(NVARCHAR,c.ENTRY_DATE,103) fecha,
                    RECEIPT_AMOUNT monto, 
                    CASH_RECEIPT_DESC descripcion
                FROM 
                    CASHRECEIPT c LEFT JOIN PEOPLE p ON c.PEOPLE_ORG_ID = p.PEOPLE_ID
                WHERE 
                    c.ENTRY_DATE BETWEEN '$fecha_inicial' 
                    AND '$fecha_final'
                    AND c.CREATE_OPID IN ('MAGARCIA','GVELEZ','MCONTRER','MMADRID','BSANCHEZ','LESTRADA')
                    AND ACADEMIC_SESSION = '$plantel'
                    AND c.VOID_FLAG = 'N'
                    AND (CASH_RECEIPT_DESC LIKE '%BA%' 
                    OR CASH_RECEIPT_DESC LIKE '%ba%'
                    OR CASH_RECEIPT_DESC LIKE '%BB%'
                    OR CASH_RECEIPT_DESC LIKE '%bb%'
                    OR CASH_RECEIPT_DESC LIKE '%SC%'
                    OR CASH_RECEIPT_DESC LIKE '%sc%')
                ORDER BY c.ENTRY_DATE ";
        $query = $this->campus->query($sql);

        return $query->result();
    }

    public function getRecibosPerDate($operator_id, $plantel = false, $fecha = false) {
        if ( !$fecha ) {
            $fecha = date('Y-m-d');
        }
        $sql = "SELECT DISTINCT
                    t1.PEOPLE_ORG_ID pid, 
                    t3.TAX_ID taxid,
                    (t3.FIRST_NAME+' '+t3.MIDDLE_NAME+' '+t3.LAST_NAME) nombre, 
                    t1.RECEIPT_NUMBER recibo, 
                    t1.RECEIPT_AMOUNT monto,
                    t2.CHARGECREDITNUMBER pago,
                    t4.ChargeAppliedTo aplicado
                FROM CASHRECEIPT t1, CHARGECREDIT t2, PEOPLE t3, ChargeCreditApplication t4
                WHERE t1.RECEIPT_NUMBER = t2.RECEIPT_NUMBER
                AND t1.PEOPLE_ORG_ID = t3.PEOPLE_ID
                AND t1.ENTRY_DATE = '$fecha' ";
        if ( $plantel ) {
            $sql.= "AND t1.ACADEMIC_SESSION = '".$plantel."' ";
        }
        if ( $operator_id != '' ) {
            $sql.= "AND t1.CREATE_OPID = '".$operator_id."' ";
        }
        
        $sql.= "AND t2.CHARGECREDITNUMBER = t4.ChargeCreditSource
                ORDER BY recibo DESC ";
        
        $query = $this->campus->query($sql);

        return $query->result();
    }
}