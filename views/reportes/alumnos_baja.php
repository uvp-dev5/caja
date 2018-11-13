<?php $this->load->view('header'); ?>

<table class="table">

<thead>
    <tr>
        <th>Matricula</th>
        <th>Id</th>
        <th>Alumno</th>
        <th>Baja</th>
        <th>Carrera</th>
        <th>Semestre</th>
        <th>Grupo</th>
        <th>Fecha de Baja</th>
    </tr>
</thead>

<tbody>
<?php foreach ( $alumnos as $a ) : ?>
    <tr>
        
    </tr>
<?php endforeach ?>
</tbody>

<tfooter>
</tfooter>

</table>

<?php $this->load->view('footer'); ?>