<?php $this->load->view('header'); ?>
<form method="post">
    <div class="form-group">
      <label for="tax_id">Matrícula</label>
      <input type="text" class="form-control" name="tax_id" id="tax_id" aria-describedby="taxid" placeholder="Matrícula">
      <small id="taxid" class="form-text text-muted">Matrícula</small>
    </div>
    <div class="form-group">
        <input type="submit" value="Consultar"/>
    </div>
</form>
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