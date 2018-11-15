<form method="post">
    <div class="form-group">
      <label for="fecha">Fecha</label>
      <input type="text" name="fecha" id="fecha" class="form-control" placeholder="Fecha" aria-describedby="fechahelp">
      <small id="fechahelp" class="text-muted">Fecha</small>
    </div>
    <div class="form-group">
      <label for="cajeros">Cajeros</label>
      <select class="form-control" name="cajeros" id="cajeros">
        <?php foreach ( $cajeros as $c ) : ?>
            <option value="<?php echo $c->id ; ?>"><?php echo $c->description ; ?></option>
        <?php endforeach ?>
      </select>
    </div>
    
</form>