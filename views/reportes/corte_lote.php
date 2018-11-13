<?php $this->load->view('header'); ?>

<table class="table">
    <thead>
        <tr>
            <th>Cajero</th>
            <th>Plantel</th>
            <th>Año</th>
            <th>Periodo</th>
            <th>Fecha de captura</th>
            <th>Monto capturado</th>
            <th>No. Lote</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $lote as $l ) : ?>
        <tr>
            <td><?php echo $l->cajero ; ?></td>
            <td><?php echo $l->ACADEMIC_SESSION ; ?></td>
            <td><?php echo $l->ACADEMIC_YEAR ; ?></td>
            <td><?php echo $l->MEDIUM_DESC ; ?></td>
            <td><?php echo $l->PAYMENT_DAY ; ?></td>
            <td><?php echo $l->total_lote ; ?></td>
            <td><?php echo $l->BATCH_NUMBER ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>