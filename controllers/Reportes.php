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