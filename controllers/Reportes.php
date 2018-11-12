<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
    public function index() {
        $this->load->model('campus/campus_model');
    }

    public function alumnos_reinscritos() {
        $planteles = $this->campus_model->planteles();
        $years = $this->campus_model->ciclos_academicos();
        $periodos = $this->campus_model->periodos_academicos();

        $modalidades = $this->campus_model->modalidades();
        $niveles = $this->campus_model->niveles_estudios();
        $carreras = $this->campus_model->programas_educativos();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('periodo', 'Periodo', 'required');
            $this->form_validation->set_rules('year', 'Año', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');
            $this->form_validation->set_rules('modalidad', 'Modalidad', 'required');
            $this->form_validation->set_rules('carrera', 'Carrera', 'trim');
            
            if ( $this->form_validation->run() ) {
                //Here should implement Reportes/alumnos_reinscritos_detalle.php
                $this->load->model('caja/alumnos_model');
                $alumnos_reinscritos = $this->alumnos_model->reinscritos(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );
                return $this->load->view(
                    '', 
                    compact('alumnos_reinscritos')
                );
            }
        }
        return $this->load->view(
            '',
            compact(
                'planteles',
                'years',
                'periodos',
                'modalidades',
                'niveles',
                'carreras'
            )
        );
    }

    public function alumnos_nuevos() {
        $planteles = $this->campus_model->planteles();
        $years = $this->campus_model->ciclos_academicos();
        $periodos = $this->campus_model->periodos_academicos();

        $modalidades = $this->campus_model->modalidades();
        $niveles = $this->campus_model->niveles_estudios();
        $carreras = $this->campus_model->programas_educativos();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('periodo', 'Periodo', 'required');
            $this->form_validation->set_rules('year', 'Año', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');
            $this->form_validation->set_rules('modalidad', 'Modalidad', 'required');
            $this->form_validation->set_rules('carrera', 'Carrera', 'trim');
            
            if ( $this->form_validation->run() ) {
                //Here should implement Reportes/alumnos_nuevos_detalle.php
                $this->load->model('caja/alumnos_model');
                $alumnos_nuevos = $this->alumnos_model->nuevos(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );
                return $this->load->view(
                    '', 
                    compact('alumnos_nuevos')
                );
            }
        }
        return $this->load->view(
            '',
            compact(
                'planteles',
                'years',
                'periodos',
                'modalidades',
                'niveles',
                'carreras'
            )
        );
    }

    public function alumnos_baja() {
        $planteles = $this->campus_model->planteles();
        $years = $this->campus_model->ciclos_academicos();
        $periodos = $this->campus_model->periodos_academicos();

        $modalidades = $this->campus_model->modalidades();
        $niveles = $this->campus_model->niveles_estudios();
        $carreras = $this->campus_model->programas_educativos();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('periodo', 'Periodo', 'required');
            $this->form_validation->set_rules('year', 'Año', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');
            $this->form_validation->set_rules('modalidad', 'Modalidad', 'required');
            $this->form_validation->set_rules('carrera', 'Carrera', 'trim');
            
            if ( $this->form_validation->run() ) {
                //Here should implement Reportes/alumnos_bajas.php
                $this->load->model('caja/alumnos_model');
                $alumnos_nuevos = $this->alumnos_model->bajas()(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),//false
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );
                return $this->load->view(
                    '', 
                    compact('alumnos_nuevos')
                );
            }
        }
        return $this->load->view(
            '',
            compact(
                'planteles',
                'years',
                'periodos',
                'modalidades',
                'niveles',
                'carreras'
            )
        );
    }

    public function corte_caja() {
        $cajeros = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');

            if ( $this->form_validation->run() ) {
                $person_code_id = "P".str_pad($this->inpu->post('id'), 9, '0', STR_PAD_LEFT);
                
                $this->load->model('campus/people_model');
                $operator_id = $this->people_model->getOperatorId($this->input->post('id'));
                /* Ask what this does and why
                if(
                    $operator_id == '' 
                    || $person_code_id == 'P000003086' 
                    || $person_code_id == 'P000003602' 
                    || $person_code_id == 'P000003894' 
                    || $person_code_id == 'P000003590' 
                    || $person_code_id == 'P000004046'
                ) {
                    $lista_upc = 1;
                    //En este caso se muestran opciones para seleccionar cajeros
                } else {
                    $lista_upc = 0;
                    //En este caso se usa el ID del usuario (clave), no se muestran opciones de cajeros
                }
                */

            }
        }

        return $this->load->view('');
    }

    public function corte_caja_detalle() {
        $cajeros = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha', 'Fecha', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');

            if ( $this->form_validation->run() ) {
                
            }
        }

        return $this->load->view('');
    }

    public function corte_caja_total() {
        $cajeros = array();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha_inicial', 'Fecha inicial', 'required');
            $this->form_validation->set_rules('fecha_final', 'Fecha final', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');

            if ( $this->form_validation->run() ) {
                
            }
        }

        return $this->load->view('');
    }

    public function reporte_vouchers() {
        $planteles = array();
        /*
        $usuarios = array('P000003083','P000003086','P000003595','P000003590','P000002219','P000003602',
            'P000003443','P000004046','P000003902');
        if ( in_array($person_code_id, $usuarios) ) {}
        */
        if ( $this->input->post() ) {
            $this->form_validation->set_rules('id', 'People ID', 'required');

            $this->form_validation->set_rules('fecha_inicial', 'Fecha inicial', 'required');
            $this->form_validation->set_rules('fecha_final', 'Fecha final', 'required');
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            
            if ( $this->form_validation->run() ) {

                return $this->load->view('');
            }
        }
        return $this->load->view('');
    }

    public function lista_corte_caja() {
        if ( $this->input->post() ) {
            $this->form_validation->set_rules('fecha_inicial', 'Fecha inicial', 'required');
            $this->form_validation->set_rules('fecha_final', 'Fecha final', 'required');
            $this->form_validation->set_rules('cajero', 'Cajero', 'required');
            

        }
        return $this->load->view('');
    }

    public function deudores() {

    }

    public function estado_cuenta() {

    }

    public function alumnos_inscritos() {

    }

    public function alumnos_inscritos_completos() {

    }
}