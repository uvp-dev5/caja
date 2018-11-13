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
                $alumnos = $this->alumnos_model->reinscritos(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );
                
                $tipo_periodo = '';
                if ( stristr($this->input->post('periodo'), 'semestre') ) {
                    $tipo_periodo = 'Semestre';
                } else if ( stristr($this->input->post('periodo'), 'cuatrim') ) {
                    $tipo_periodo = 'Cuatrimestre';
                }

                return $this->load->view(
                    'caja/reportes/alumnos.php', 
                    compact('alumnos', 'tipo_periodo')
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
                $alumnos = $this->alumnos_model->nuevos(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );

                $tipo_periodo = '';
                if ( stristr($this->input->post('periodo'), 'semestre') ) {
                    $tipo_periodo = 'Semestre';
                } else if ( stristr($this->input->post('periodo'), 'cuatrim') ) {
                    $tipo_periodo = 'Cuatrimestre';
                }

                return $this->load->view(
                    'caja/reportes/alumnos', 
                    compact('alumnos', 'tipo_periodo')
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
                $alumnos = $this->alumnos_model->bajas()(
                    $this->input->post('plantel'),
                    $this->input->post('periodo'),
                    $this->input->post('year'),
                    $this->input->post('nivel'),//false
                    $this->input->post('modalidad'),
                    $this->input->post('carrera')//false
                );

                $tipo_periodo = '';
                if ( stristr($this->input->post('periodo'), 'semestre') ) {
                    $tipo_periodo = 'Semestre';
                } else if ( stristr($this->input->post('periodo'), 'cuatrim') ) {
                    $tipo_periodo = 'Cuatrimestre';
                }                

                return $this->load->view(
                    'caja/reportes/alumnos_baja', 
                    compact('alumnos', 'tipo_periodo')
                );
            }
        }
        return $this->load->view(
            'caja/reportes/alumnos_baja',
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
        redirect('caja/corte/caja');
    }

    public function corte_caja_detalle() {
        redirect('caja/corte/caja_detalle');
    }

    public function corte_caja_total() {
        redirect('caja/corte/caja_total');
    }

    public function reporte_vouchers() {
        redirect('caja/corte/vouchers');
    }

    public function deudores() {
        $planteles = $this->campus_model->planteles();
        $years = $this->campus_model->ciclos_academicos();
        $periodos = $this->campus_model->periodos_academicos();
        $modalidades = $this->campus_model->modalidades();
        $niveles = $this->campus_model->niveles_estudios();

        if ( $this->input->post() ) {
            $this->form_validation->set_rules('plantel', 'Plantel', 'required');
            $this->form_validation->set_rules('periodo', 'Periodo', 'required');
            $this->form_validation->set_rules('year', 'Año', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');
            $this->form_validation->set_rules('modalidad', 'Modalidad', 'required');
            //Implement deudores_detalle.php
        }
    }

    public function estado_cuenta() {
        $planteles = $this->campus_model->planteles();
        $years = $this->campus_model->ciclos_academicos();
        $periodos = $this->campus_model->periodos_academicos();
        if ( $this->input->post() ) {
            $this->form_validation->set_rule('tipo', '', 'required');
            $this->form_validation->set_rule('id_pc', '', 'required');
            $this->form_validation->set_rule('plantel', '', 'required');
            $this->form_validation->set_rule('year', '', 'required');
            $this->form_validation->set_rule('periodo', '', 'required');
            $this->form_validation->set_rule('matricula', '', 'required');
            $this->form_validation->set_rule('nombre', '', 'required');
            $this->form_validation->set_rule('clave_usuario', '', 'required');
            //Implement estados_cuenta.php
        }
        return $this->load->model('', compact('planteles', 'years', 'periodos'));
    }

    public function alumnos_inscritos() {
        //Check admisiones/rep_inscritos.php
    }

    public function alumnos_inscritos_completos() {
        //Check admisiones/rep_inscritos_completos.php
    }
}