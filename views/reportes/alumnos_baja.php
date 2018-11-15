<?php $this->load->view('header'); ?>

<?php $this->load->view('caja/reportes/_alumnos_form'); ?>

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
        <td><?php echo $a->TAX_ID ; ?></td>
        <td><?php echo $a->ID ; ?></td>
        <td><?php echo utf8_encode($a->NOMBRE) ; ?></td>
        <td><?php echo $a->BAJA ; ?></td>
        <td><?php echo $a->CODE_XDESC ; ?></td>
        <td><?php echo $a->ULTIMO_SEMESTRE ; ?></td>
        <td><?php echo $a->GRUPO ; ?></td>
        <td><?php echo $a->FECHA_BAJA ; ?></td>
    </tr>
<?php endforeach ?>
</tbody>

<tfooter>
</tfooter>

</table>

<?php $this->load->view('footer'); ?>