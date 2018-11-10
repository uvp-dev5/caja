<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recibos_model extends CI_Model {
    
    private $table = 'caja_recibos';
    private $janus;

    public function __construct() {
        parent::__construct();
        $this->janus = $this->load->database('janus', TRUE);
    }

    public function list() {
    }

    public function get() {
    }

    public function getByPlantelAndDate($plantel, $fecha, $cancelado = false) {
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
        $query = $this->janus->get($this->table);

        return $query->result();
    }

    public function corte($plantel, $fecha, $cajero, $cancelado = false) {
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
        
        $query = $this->get($this->table);

        return $query->row();
    }

    public function create() {
    }

    public function update() {
    }

    public function delete() {
    }
}