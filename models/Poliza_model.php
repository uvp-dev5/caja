<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poliza_model extends CI_Model {
    
    private $table = 'caja_polizas';
    private $janus;

    public function __construct() {
        parent::__construct();
        $this->janus = $this->load->database('janus', TRUE);
    }

    public function getCorte($plantel, $fecha, $cajero, $cancelado = false) {
        $this->janus->select_max('no_poliza', 'maximo');
        $this->janus->select_min('no_poliza', 'minimo');
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
        
        $query = $this->get($this->table);

        return $query->row();
    }

    public function listByPlantelAndDate($plantel, $fecha, $cancelado = false) {
        $this->janus->select('no_poliza, matricula, descri, importe, DATE_FORMAT(fecha_opera,"%d/%m/%Y") AS fecha_opera, tipo_pago');
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
        $query = $this->janus->get($this->table);

        return $query->result();
    }
}