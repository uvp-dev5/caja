<?php $this->load->view('header'); ?>

<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Matricula</th>
            <th>Nombre</th>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Recibo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $recibos as $r ) : ?>
        <tr>
            <td><?php echo $r->pid ; ?></td>
            <td><?php echo $r->taxid ; ?></td>
            <td><?php echo utf8_encode($r->nombre) ; ?></td>
            <td><?php echo $r->concepto ; ?></td>
            <td><?php echo $r->monto ; ?></td>
            <td><?php echo $r->recibo ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>