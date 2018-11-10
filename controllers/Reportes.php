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
            $this->form_validation->set_rules('modalidad', 'Modalidad', 'required');
            $this->form_validation->set_rules('nivel', 'Nivel', 'required');
            $this->form_validation->set_rules('years', 'Año', 'required');
            $this->form_validation->set_rules('periodo', 'Periodo', 'required');
            if ( this->form_validation->run() ) {
                //Here should implement Reportes/alumnos_reinscritos_detalle.php
                return $this->load->view(
                    'view', 
                    compact()
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

    }

    public function alumnos_baja() {

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