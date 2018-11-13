<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja_model extends CI_Model {

    private $janus;
    private $campus;
    private $nom;

    public function __construct() {
        parent::__construct();
        $this->janus = $this->load->database('janus', TRUE);
        $this->campus = $this->load->database('campus', TRUE);
        $this->nom = $this->load->database('nom', TRUE);
    }

    public function getCajeros() {
        $sql = "SELECT DISTINCT realizo ";
        $sql.= "FROM caja_polizas ";
        $sql.= "WHERE realizo <> '' ";
        
        $query = $this->janus->query($sql);

        return $query->result();
    }

    public function getCajerosByFecha($fecha = false, $plantel = false) {
        $sql = "SELECT DISTINCT ";
        $sql.= "b.CREATE_OPID AS usuario, ";
        $sql.= "a.PEOPLE_CODE_ID AS upc,  ";
        $sql.= "b.ACADEMIC_SESSION AS plantel ";
        $sql.= "FROM ";
        $sql.= "BATCHHEADER b LEFT JOIN ABT_USERS a ";
        $sql.= "ON b.CREATE_OPID = A.OPERATOR_ID ";
        if ( !$fecha ) {
            if ( !$plantel ) {
                $sql.= "WHERE b.CREATE_OPID IN ('MAGARCIA','GVELEZ','MMADRID','MCONTRER', ";
                $sql.= "'LESTRADA','BSANCHEZ','MORTIZ','MESPINOS','KCARRERA','OXXO','WEB', ";
                $sql.= "'CARD','SPEI','IntServ') ";
                $sql.= "AND b.ACADEMIC_SESSION <> '' ";
                
                $sql.= "ORDER BY a.PEOPLE_CODE_ID DESC ";
            } else {
                if ( $plantel == 'PUEBLA' ) {
                    $sql.= "WHERE b.CREATE_OPID IN ('MAGARCIA','GVELEZ','MMADRID','MCONTRER', ";
                    $sql.= "'MORTIZ','MESPINOS','OXXO','WEB','CARD','SPEI','IntServ') ";
                    $sql.= "AND b.ACADEMIC_SESSION <> '' ";
                    
                    $sql.= "ORDER BY a.PEOPLE_CODE_ID DESC ";
                } else {
                    $sql.= "WHERE b.CREATE_OPID IN ('LESTRADA','BSANCHEZ','KCARRERA','OXXO',";
                    $sql.= "'MESPINOS','WEB','CARD','SPEI','IntServ') ";
                    $sql.= "AND b.ACADEMIC_SESSION <> '' ";
                    
                    $sql.= "ORDER BY a.PEOPLE_CODE_ID DESC ";
                }
            }
        } else {
            $sql.= "WHERE b.BATCH_DATE = '$fecha' ";
            $sql.= "AND b.ITEMS_ENTERED > 0 ";
            $sql.= "AND b.CREATE_OPID <> 'IURIZAR' ";
        }

        $query = $this->campus->query($sql);

        return $query->result();
    }

    public function getCajeroUPCByPeopleCode($people_code) {
        $this->nom->select("cveubi, (nombre + ' ' + ' ' + apepat + ' ' + apemat) as upc");
        $this->nom->where(array(
            'cvetra' => $people_code
        ));
        $query = $this->nom->get('nomtrab');

        return $query->row()->upc;
    }

    public function getCajeroByClave($clave) {
        $this->nom->select("cveubi as clave_ubicacion, (nombre + ' ' + ' ' + apepat + ' ' + apemat) as nombre_completo");
        $this->nom->where(array(
            'cvetra' => $clave
        ));
        $query = $this->nom->get('nomtrab');

        return $query->row();
    }

    public function getOperatorIdByPeopleCode($people_code) {
        $this->campus->select('operator_id');
        $this->campus->where(array(
            'people_code_id' => $people_code
        ));
        $query = $this->campus->get('abt_users');

        return $query->row()->operator_id;
    }

    public function totalLote($batch_number) {
        $this->campus->select_sum('RECEIPT_AMOUNT', 'suma');
        $this->campus->where(array(
            'batch' => $batch_number,
            'void_flag', 'N'
        ));
        $query = $this->campus->get('cashreceipt');

        return $query->row()->suma;
    }
}