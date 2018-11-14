<?php $this->load->view('header') ;?>

<form method="post">
    <div class="form-group">
    <label for="plantel">Plantel</label>
    <select class="form-control" name="plantel" id="plantel">
        <?php foreach ( $planteles as $p ) : ?>
            <option 
                value="<?php echo $p->id ; ?>" 
                <?php echo set_select('plantel', $p->id);?>>
                    <?php echo $p->description ; ?>
            </option>
        <?php endforeach ?>
    </select>
    </div>
    <div class="form-group">
      <label for="cajero">Cajero</label>
      <select class="form-control" name="cajero" id="cajero">
        <?php foreach ( $cajeros as $c ) : ?>
            <option value="<?php echo $c->id ; ?>"><?php echo $c->description ; ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group">
    <label for="fecha">Día</label>
    <input 
        type="date" class="form-control" 
        name="fecha" id="fecha" 
        aria-describedby="fecha" 
        placeholder="Fecha"
        value="<?php echo set_value('fecha');?>">
    <small id="fecha" class="form-text text-muted">Fecha inicial</small>
    </div>
    <div class="form-group">
      <button type="submit">Consultar</button>
    </div>
</form>

<h2>Corte de Recibos</h2>
<table class="table">
    <thead>
        <tr>
            <th>Folio inicial</th>
            <th>Folio final</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $corte_recibos->minimo ; ?></td>
            <td><?php echo $corte_recibos->maximo ; ?></td>
            <td><?php echo $corte_recibos->total ; ?></td>
        </tr>
    </tbody>
    <tfooter>
    </tfooter>
</table>

<h2>Corte de Polizas</h2>
<table class="table">
    <thead>
        <tr>
            <th>Folio inicial</th>
            <th>Folio final</th>
            <th>Importe</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $corte_polizas->minimo ; ?></td>
            <td><?php echo $corte_polizas->maximo ; ?></td>
            <td><?php echo $corte_polizas->total ; ?></td>
        </tr>
    </tbody>
    <tfooter>
    </tfooter>
</table>
<?php $this->load->view('footer') ;?>