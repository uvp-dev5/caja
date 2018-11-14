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
<h2>Recibos por Plantel</h2>
<h3>Recibos</h3>
<table class="table">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Fecha</th>
            <th>Importe</th>
            <th>No de recibo</th>
            <th>Descripción</th>
            <th>Tipo de Pago</th>
        </tr>
    </thead>
    
    <tbody>
    <?php foreach ( $recibos as $i ) : ?>
    <tr>
        <td><?php echo $i->matricula ; ?></td>
        <td><?php echo $i->fecha_opera ; ?></td>
        <td><?php echo $i->importe ; ?></td>
        <td><?php echo $i->no_recibo ; ?></td>
        <td><?php echo $i->descri ; ?></td>
        <td><?php echo $i->tipo_pago ; ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
    
    <tfooter></tfooter>
</table>

<h3>Recibos faltantes</h3>
<table class="table">
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Fecha</th>
            <th>Importe</th>
            <th>No de recibo</th>
            <th>Descripción</th>
            <th>Tipo de Pago</th>
        </tr>
    </thead>
    
    <tbody>
    <?php foreach ( $faltantes_recibos as $i ) : ?>
    <tr>
        <td><?php echo $i->matricula ; ?></td>
        <td><?php echo $i->fecha_opera ; ?></td>
        <td><?php echo $i->importe ; ?></td>
        <td><?php echo $i->no_recibo ; ?></td>
        <td><?php echo $i->descri ; ?></td>
        <td><?php echo $i->tipo_pago ; ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
    
    <tfooter></tfooter>
</table>

<h2>Polizas por Plantel</h2>
<h3>Polizas</h3>
<table class="table">
    <thead>
        <th>Matrícula</th>
        <th>Fecha</th>
        <th>Importe</th>
        <th>No de poliza</th>
        <th>Descripción</th>
        <th>Tipo de Pago</th>
    </thead>
    
    <tbody>
    <?php foreach ( $polizas as $i ) : ?>
    <tr>
        <td><?php echo $i->matricula ; ?></td>
        <td><?php echo $i->fecha_opera ; ?></td>
        <td><?php echo $i->importe ; ?></td>
        <td><?php echo $i->no_poliza ; ?></td>
        <td><?php echo $i->descri ; ?></td>
        <td><?php echo $i->tipo_pago ; ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
    
    <tfooter></tfooter>
</table>

<h3>Polizas faltantes</h3>
<table class="table">
    <thead>
        <th>Matrícula</th>
        <th>Fecha</th>
        <th>Importe</th>
        <th>No de poliza</th>
        <th>Descripción</th>
        <th>Tipo de Pago</th>
    </thead>
    
    <tbody>
    <?php foreach ( $faltantes_polizas as $i ) : ?>
        <td><?php echo $i->matricula ; ?></td>
        <td><?php echo $i->fecha_opera ; ?></td>
        <td><?php echo $i->importe ; ?></td>
        <td><?php echo $i->no_poliza ; ?></td>
        <td><?php echo $i->descri ; ?></td>
        <td><?php echo $i->tipo_pago ; ?></td>
    <?php endforeach ?>
    </tbody>
    
    <tfooter></tfooter>
</table>

<?php $this->load->view('footer') ;?>