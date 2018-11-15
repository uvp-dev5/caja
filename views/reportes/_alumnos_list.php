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
<?php $counter = 1; ?>
<?php foreach ( $alumnos as $a ) : ?>
    <tr>
        <td><?php echo $counter ; ?></td>
        <td><?php echo $a->MATRICULA ; ?></td>
        <td><?php echo $a->IDPC ; ?></td>
        <td><?php echo utf8_encode($a->NOMBRE) ; ?></td>
        <td><?php echo $a->EMAIL_ADDRESS ; ?></td>
        <td><?php echo $a->PHONE_NUMBER ; ?></td>
        <td><?php echo utf8_encode($a->CARRERA) ; ?></td>
        <td><?php echo $a->CONCEPTO ; ?></td>
        <td><?php echo $a->PAGO ; ?></td>
        <td><?php echo $a->FECHA_PAGO ; ?></td>
        <td><?php echo isset($proceso) ? $proceso : $a->proceso ; ?></td>
    </tr>
    <?php $counter++; ?>
<?php endforeach ?>
</tbody>

<tfooter>
</tfooter>

</table>

<?php $this->load->view('footer'); ?>