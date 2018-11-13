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
            <td><?php echo $v->recibo ; ?></td>
            <td><?php echo $v->idpc ; ?></td>
            <td><?php echo $v->matricula ; ?></td>
            <td><?php echo $v->alumno ; ?></td>
            <td><?php echo $v->lote ; ?></td>
            <td><?php echo $v->fecha ; ?></td>
            <td><?php echo $v->monto ; ?></td>
            <td><?php echo $v->descripcion ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>