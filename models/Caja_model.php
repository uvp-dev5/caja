<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja_model extends CI_Model {

    private $janus;

    public function __construct() {
        parent::__construct();
        $this->janus = $this->load->database('janus', TRUE);
    }

    public function getCajeros() {
        $sql = "SELECT DISTINCT realizo ";
        $sql.= "FROM caja_polizas ";
        $sql.= "WHERE realizo <> '' ";
        
        $query = $this->janus->query($sql);

        return $query->result();
    }
}