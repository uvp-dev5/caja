<?php $this->load->view('header'); ?>

<table class="table">
    <thead>
        <tr>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $recibos as $r ) : ?>
        <tr>
            <td><?php echo $r->concepto ; ?></td>
            <td><?php echo $r->monto ; ?></td>
            <td><?php echo $r->recibo ; ?></td>
            <td><?php echo $r->fecha ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>