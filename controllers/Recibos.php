<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recibos extends CI_Controller {
    
    public function por_dia() {
        if ( $this->input->post() ) {
            $this->form_validation->set_rule('id', 'Clave', 'required');
            if ( $this->form_validation->run() ) {
                $clave = substr($this->input->post('id'), 2);
                $people_code = "P".str_pad($this->input->post('id'), 9, "0", STR_PAD_LEFT);
                
                $this->load->model('caja/caja_model');
                $usuario = $this->caja_model->getCajeroByClave($clave);
                $operator_id = $this->caja_model->getOperatorIdByPeopleCode($people_code);

                $plantel = false;
                if ($usuario->ubicacion_clave == '00001') {
                    $plantel = 'PUEBLA';
                } elseif ($usuario->ubicacion_clave == '00002') {
                    $plantel = 'TEHUA';
                }

                $recibos = $this->cajero_model->getRecibosPerDate($operator_id, $plantel);
                $this->load->model('caja/recibo_model');
                foreach ( $recibos as $r ) {
                    $detalles_compra = $this->recibo_model->getInfoByComprador($r->aplicado);
                    $r->concepto = $detalles_compra->cargo;
                    $r->operacion_id = $detalles_compra->opid;
                }

                $this->load->view('caja/reportes/recibos', compact('recibos'));
            }
        }
        return $this->load->view('caja/reportes/recibos');
    }

    public function por_recibo_id() {
        if ( $this->input->post() ) {
            $this->form_validation->set_rule('recibo_id', 'Número de recibo', 'required');
            $this->form_validation->set_rule('recibo_tipo', 'Tipo de recibo', 'required');

            if ( $this->form_validation->run() ) {
                $this->load->model('caja/recibo_model');
                $recibo = $this->recibo_model->getInfoById($this->input->post('recibo_id'));

                if ( is_null($recibo->TAX_ID) || ($recibo->TAX_ID == '') ) {
                    //Si el TAX_ID no existe hay que obtenerlo, ver recibos/verificacion.php, linea 119
                    /**
                     * Si el people_id (matricula) del recibo es alguna de las siguientes
                     * '000110011', '000110012', '001100011', '001100012', '001100013'
                     * No se buscan ni listan las carreras en las que esta inscrito
                    */
                    $this->load->model('campus/alumno_model');
                    $carreras = $this->alumno_model->listCarrerasByPeopleId($recibo->MATRICULA);
                }
                //Una vez contemos con un TAX_ID ver recibo.php y recibo_p.php 
            }
        }
        return $this->load->view('caja/reportes/recibo_por_id');
    }

    public function por_persona() {
        if ( $this->input->post() ) {
            $this->form_validation_rule('tax_id', 'Tax ID', 'required');

            if ( $this->form_validation->run() ) {
                $this->load->model('campus/people_model');
                $this->load->model('caja/recibo_model');

                $people = $this->people_model->getByTaxId($this->input->post('tax_id'));
                $recibos = $this->recibo_model->listByComprador($people->matricula);
                
                foreach ( $recibos as $r ) {
                    $detalles_compra = $this->recibo_model->getInfoByComprador($r->aplicado);
                    $r->concepto = $detalles_compra->cargo;
                    $r->operacion_id = $detalles_compra->opid;
                }

                return $this->load->view('caja/reportes/recibos_por_persona', compact('recibos'));
            }
        }
        return $this->load->view('recibos_por_persona');
    }

    public function por_lote() {
        if ( $this->input->post() ) {
            $this->form_validation->set_rule('lote_id', 'Número de lote', 'required');

            if ( $this->form_validation->run() ) {
                $this->load->model('caja/recibo_model');
                
                $recibos = $this->recibo_model->listByLote($this->input->post('lote_id'));

                return $this->load->view('caja/reportes/recibos_por_lote', compact('recibos'));
            }
        }

        return $this->load->view('caja/reportes/recibos_por_lote');
    }
}