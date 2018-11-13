<?php $this->load->view('header'); ?>

<table class="table">
    <thead>
        <tr>
            <th>Recibo</th>
            <th>Power Campus</th>
            <th>Matricula</th>
            <th>Alumno</th>
            <th>Lote</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $vouchers as $v ) : ?>
        <tr>
            <td><?php echo $l->recibo ; ?></td>
            <td><?php echo $l->idpc ; ?></td>
            <td><?php echo $l->matricula ; ?></td>
            <td><?php echo $l->alumno ; ?></td>
            <td><?php echo $l->lote ; ?></td>
            <td><?php echo $l->fecha ; ?></td>
            <td><?php echo $l->monto ; ?></td>
            <td><?php echo $l->descripcion ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>