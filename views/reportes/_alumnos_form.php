<form method="post">
    <div class="form-group">
      <label for="plantel">Plantel</label>
      <select class="form-control" name="plantel" id="plantel">
        <?php foreach ( $planteles as $p ) : ?>
            <option value="<?php echo $p->id ; ?>">
                <?php echo $p->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="periodo">Periodo</label>
      <select class="form-control" name="periodo" id="periodo">
        <?php foreach ( $periodos as $p ) : ?>
            <option value="<?php echo $p>-id;?>">
                <?php echo $p->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>
    
    <div class="form-group">
      <label for="year">Año</label>
      <select class="form-control" name="year" id="year">
        <?php foreach ( $years as $y ) : ?>
            <option value="<?php echo $y->id ; ?>">
                <?php echo $y->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="nivel">Nivel</label>
      <select class="form-control" name="nivel" id="nivel">
        <?php foreach ( $niveles as $n ) : ?>
            <option value="<?php echo $n->id ; ?>">
                <?php echo $n->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="modalidad">Modalidad</label>
      <select class="form-control" name="modalidad" id="modalidad">
        <?php foreach ( $modalidades as $m ) : ?>
            <option value="<?php echo $m->id ; ?>">
                <?php echo $m->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
      <label for="carrera">Carrera</label>
      <select class="form-control" name="carrera" id="carrera">
        <?php foreach ( $carreras as $c ) : ?>
            <option value="<?php echo $c->id ; ?>">
                <?php echo $c->description ; ?>
            </option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Consultar">
    </div>
</form>