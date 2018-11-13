<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corte extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('campus/people_model');
    }

    public function caja() {
        $cajeros = array();
        $lote = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');
            
            if ( $this->form_validation->run() ) {
                $cajero = $this->input->post('cajero');
                $plantel = false;
                $fecha = $this->input->post('fecha');
                
                $this->prepareReportVariables($this->input->post('id'), $cajero, $plantel);

                $this->load->model('caja/cajero_model');
                $lote = $this->cajero_model->getLotesPerDay($cajero, $plantel, $fecha);

                foreach ( $lote as $l ) {
                    $this->load->model('caja/caja_model');
                    
                    $cajero_temp = $this->caja_model->getCajeroUPCByPeopleCode(substr($lote->PEOPLE_CODE_ID, 3));
                    $l->cajero = ( $cajero_temp != '' ) ? $cajero_temp : $l->CREATE_OPID;
                    $l->total_lote = $this->caja_model->totalLote($l->BATCH_NUMBER);
                }

                $this->load->view('caja/reportes/corte/lote', compact('lote'));
            }
        }

        return $this->load->view('caja/reportes/corte/lote', compact('lote'));
    }

    public function caja_detalle() {
        $cajeros = array();
        $lote = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');

            if ( $this->form_validation->run() ) {
                $cajero = $this->input->post('cajero');
                $plantel = false;
                $fecha = $this->input->post('fecha');

                $this->prepareReportVariables($this->input->post('id'), $cajero, $plantel);

                $this->load->model('caja/cajero_model');
                $lotes = $this->cajero_model->getLotesPerDay($cajero, $plantel, $fecha);

                foreach ( $lote as $l ) {
                    $this->load->model('caja/caja_model');
                    
                    $cajero_temp = $this->caja_model->getCajeroUPCByPeopleCode(substr($lote->PEOPLE_CODE_ID, 3));
                    $l->cajero = ( $cajero_temp != '' ) ? $cajero_temp : $l->CREATE_OPID;
                    $l->total_lote = $this->caja_model->totalLote($l->BATCH_NUMBER);
                }

                $this->load->view('caja/reportes/corte/lote', compact('lote'));
            }
        }

        return $this->load->view('caja/reportes/corte/lote', compact('lote'));
    }

    public function caja_total() {
        $cajeros = array();
        $lote = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha_inicial', 'Fecha inicial', 'required');
            $this->form_validation->set_rules('fecha_final', 'Fecha final', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');

            if ( $this->form_validation->run() ) {
                $cajero = $this->input->post('cajero');
                $plantel = false;
                $fecha_inicial = $this->input->post('fecha_inicial');
                $fecha_final = $this->input->post('fecha_final');

                $this->prepareReportVariables($this->input->post('id'), $cajero, $plantel);
                
                $this->load->model('caja/cajero_model');
                $lotes = $this->cajero_model->getLotesPerDay($cajero, $plantel, $fecha_inicial, $fecha_final);

                foreach ( $lote as $l ) {
                    $this->load->model('caja/caja_model');
                    
                    $cajero_temp = $this->caja_model->getCajeroUPCByPeopleCode(substr($lote->PEOPLE_CODE_ID, 3));
                    $l->cajero = ( $cajero_temp != '' ) ? $cajero_temp : $l->CREATE_OPID;
                    $l->total_lote = $this->caja_model->totalLote($l->BATCH_NUMBER);
                }

                $this->load->view('caja/reportes/corte/lote', compact('lote'));
            }
        }

        return $this->load->view('caja/reportes/corte/lote', compact('lote'));
    }

    public function vouchers() {
        $planteles = array();
        $vouchers = array();
        /*
        $usuarios = array('P000003083','P000003086','P000003595','P000003590','P000002219','P000003602',
            'P000003443','P000004046','P000003902');
        if ( in_array($person_code_id, $usuarios) ) {
            this function is only accesible if this condition is met
        }
        */
        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha_inicial', 'Fecha inicial', 'required');
            $this->form_validation->set_rules('fecha_final', 'Fecha final', 'required');
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            
            if ( $this->form_validation->run() ) {
                $this->load->model('caja/cajero_model');
                $vouchers = $this->cajero_model->getVouchersPerDay($plantel, $fecha_inicial, $fecha_final);
                return $this->load->view('caja/reportes/corte/voucher', compact('vouchers'));
            }
        }
        return $this->load->view('caja/reportes/corte/voucher', compact('vouchers'));
    }

    /**
     * Checks if the operator has priviledges to generate reports for himselft or other operators
     *
     * @param [type] $id
     * @return boolean
     */
    private function isAdmin($id) {
        $person_code_id = "P".str_pad($id, 9, '0', STR_PAD_LEFT);        
        $operator_id = $this->people_model->getOperatorId($person_code_id);
        if(
            $operator_id == '' 
            || $person_code_id == 'P000003086' 
            || $person_code_id == 'P000003602' 
            || $person_code_id == 'P000003894' 
            || $person_code_id == 'P000003590' 
            || $person_code_id == 'P000004046'
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Alters variables for generating the reports needed, it receives by reference:
     *
     * @param [type] $id Current operator ID
     * @param [type] $cajero|by reference Selected cajero, if user has permission to generate reports for other users, check isAdmin
     * @param [type] $plantel|by reference If this report should take into account plantel, this is determined by this function
     * @return void
     */
    private function prepareReportVariables($id, &$cajero, &$plantel) {
        if ( $this->isAdmin($id) ) {
            $clave = $cajero;
        } else {
            $clave = $id;
        }

        if ( is_numeric($clave) ) {
            $clave = substr($cajero, 2);
            $person_code_id = "P" . str_pad($clave, 9, "0", STR_PAD_LEFT);
            $operator_id = $this->people_model->getOperatorId($person_code_id);
            
            if ( $operator_id != "" ) {
                $cajero = $operator_id;
            }
        } else {
            /* 
            * Como los valores del cajero cuando este no tiene PEOPLE_CODE (no es una persona)
            * son asignados en el formato IDENTIFICADOR-PLANTEL, obtendremos y asignaremos 
            * dichos valores a variables
            */
            $cajero_details = explode('-', $this->input->post('cajero'));
            $cajero = $cajero_details[0];
            $plantel = $cajero_details[1];
        }
    }
}