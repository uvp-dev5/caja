<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja extends CI_Controller {
    
    public function index() {
        
    }

    public function imprimir_recibos() {
        redirect('caja/recibos');
    }

    public function reportes() {
        redirect('caja/reportes');
    }

    public function plantel_pagos() {
        $this->load->model('campus/campus_model');
        $planteles = $this->campus_model->planteles();
        
        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            if ( $this->form_validation->run() ) {
                
                $plantel = ( $this->input->post('plantel') == "PUEBLA" ) ? 1 : 2 ;
                
                $this->load->model('caja/recibo_model');
                $this->load->model('caja/poliza_model');
                $this->load->model('campus/People');
                
                $recibos = $this->recibo_model->listByPlantelAndDate(
                    $plantel, 
                    $this->input->post('fecha')
                );
                $faltantes_recibos = array();
                $polizas = $this->poliza_model->listByPlantelAndDate(
                    $plantel,
                    $this->input->post('fecha')
                );
                $faltantes_polizas = array();
                
                foreach( $recibos as $r ) {
                    if ( $r->matricula == "VAR" ) {
                        $r->matricula = ($plantel == 1) ? '000100012' : '000100013' ;
                    } else {
                        $people = $this->people_model->getByTaxId($r->matricula);
                        if ( $people->matricula == '' ) {
                            $faltantes_recibos[] = $r;
                        } else {
                            $r->matricula = $people->matricula;
                        }
                    }
                }
                
                foreach( $polizas as $p ) {
                    if ( $p->matricula == "VAR" ) {
                        $p->matricula = ($plantel == 1) ? '000100012' : '000100013' ;
                    } else {
                        $people = $this->people_model->getByTaxId($p->matricula);
                        if ( $people->matricula == '' ) {
                            $faltantes_polizas[] = $p;
                        } else {
                            $p->matricula = $people->matricula;
                        }
                    }
                }
                
                return $this->load->view(
                    'plantel_pagos', 
                    compact(
                        'recibos', 
                        'polizas', 
                        'faltantes_recibos',
                        'faltantes_polizas'
                    )
                );
            }
        }
        return $this->load->view( 'plantel_pagos', compact('planteles') );
    }

    public function corte_por_plantel() {
        $this->load->model('campus/campus_model');
        $this->load->model('caja/caja_model');

        $planteles = $this->campus_model->planteles();
        $cajeros = $this->caja_model->getCajeros();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');
            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            if ( $this->form_validation->run() ) {
                
                $plantel = ( $this->input->post('plantel') == "PUEBLA" ) ? 1 : 2 ;
                
                $this->load->model('caja/recibo_model');
                $this->load->model('caja/poliza_model');
                
                $corte_recibos = $this->recibo_model->getCorte(
                    $plantel,
                    $this->input->post('fecha'),
                    $this->input->post('cajero')
                );
                $corte_polizas = $this->poliza_model->getCorte(
                    $plantel,
                    $this->input->post('fecha'),
                    $this->input->post('cajero')
                );

                return $this->load->view(
                    'plantel_corte', 
                    compact(
                        'corte_recibos',
                        'corte_polizas'
                    )
                );
            }
        }
        return $this->load->view(
            'plantel_corte', 
            compact(
                'planteles', 
                'cajeros'
            ) 
        );
    }
}