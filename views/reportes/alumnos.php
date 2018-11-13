<?php $this->load->view('header'); ?>

<table class="table">

<thead>
    <tr>
        <th>#</th>
        <th>Matricula</th>
        <th>Id</th>
        <th>Alumno</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Carrera</th>
        <th><?php echo $tipo_periodo ; ?></th>
        <th>Concepto</th>
        <th>Pago</th>
        <th>Fecha de pago</th>
        <th>Proceso</th>
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