<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    
    public function get_cajeros_por_fecha() {
        if ( !$this->input->post() ) {
            print_r(json_encode(array()));
            exit();
        }
        
        $this->form_validation->set_rules('fecha', 'Fecha', 'trim');
        $this->form_validation->set_rules('plantel', 'Plantel', 'trim');
        
        if ( !$this->form_validation->run() ) {
            print_r(json_encode(array()));
            exit();
        }
        
        $this->load->model('caja/caja_model');
        $fecha = DateTime::createFromFormat('Y-m-d', $this->input->post('fecha'));
        $cajeros = $this->caja_model->getCajerosByFecha($fecha, $this->input->post('plantel'));

        foreach ( $cajeros as $c ) {
            if ( $c->upc != '' ) {
                $people_code = substr($c->upc, 3);

                $c->value = $people_code;
                $c->description = utf8_encode($this->caja_model->getUPCByPeopleCode($people_code));
            } else {
                ##PAGOS EN LINEA, PORTAL UVP
                if ($c->usuario == 'WEB' && $c->plantel == 'PUEBLA') {
                    $c->value = "WEB-PUEBLA";
                    $c->description = "PAGOS EN LINEA PUEBLA";
                } 
                elseif ($c->usuario == 'WEB' && $c->plantel == 'TEHUA') {
                    $c->value = "WEB-TEHUA";
                    $c->description = "PAGOS EN LINEA TEHUACAN";
                }
                ##CONCILIACION BANCARIA
                elseif ($c->usuario == 'IntServ' && $c->plantel == 'PUEBLA') {
                    $c->value = "IntServ-PUEBLA";
                    $c->description = "CONCILIACION BANCARIA PUEBLA";
                }
                elseif ($c->usuario == 'IntServ' && $c->plantel == 'TEHUA') {
                    $c->value = "IntServ-TEHUA";
                    $c->description = "CONCILIACION BANCARIA TEHUACAN";
                }
                ##PAGOS EN OXXO
                elseif ($c->usuario == 'OXXO' && $c->plantel == 'PUEBLA') {
                    $c->value = "OXXO-PUEBLA";
                    $c->description = "OXXO PUEBLA";
                }
                elseif ($c->usuario == 'OXXO' && $c->plantel == 'TEHUA') {
                    $c->value = "OXXO-TEHUA";
                    $c->description = "OXXO TEHUACAN";
                }
                ##PAGOS CON TARJETA
                elseif ($c->usuario == 'CARD' && $c->plantel == 'PUEBLA') {
                    $c->value = "CARD-PUEBLA";
                    $c->description = "PAGOS CON TARJETA PUEBLA";
                }
                elseif ($c->usuario == 'CARD' && $c->plantel == 'TEHUA') {
                    $c->value = "CARD-TEHUA";
                    $c->description = "PAGOS CON TARJETA TEHUACAN";
                }
                ##PAGOS CON SPEI
                elseif ($c->usuario == 'SPEI' && $c->plantel == 'PUEBLA') {
                    $c->value = "SPEI-PUEBLA";
                    $c->description = "PAGOS SPEI PUEBLA";
                } 
                elseif ($c->usuario == 'SPEI' && $c->plantel == 'TEHUA') {
                    $c->value = "SPEI-TEHUA";
                    $c->description = "PAGOS SPEI TEHUACAN";
                }
            }
        }

        print_r(json_encode($cajeros));
        exit();
    }

}