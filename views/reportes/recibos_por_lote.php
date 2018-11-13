<?php $this->load->view('header'); ?>

<table class="table">
    <thead>
        <tr>
            <th>Folio</th>
            <th>Id PC</th>
            <th>Tax Id</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>No Recibo</th>
            <th>Descripción</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $recibos as $r ) : ?>
        <tr>
            <td><?php echo $r->matricula ; ?></td>
            <td><?php echo $r->tax_id ; ?></td>
            <td><?php echo utf8_encode($r->alumno) ; ?></td>
            <td><?php echo $r->charge_credit_code ; ?></td>
            <td><?php echo $r->importepago ; ?></td>
            <td><?php echo $r->receipt_number ; ?></td>
            <td><?php echo $r->crg_crd_desc ; ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer'); ?>