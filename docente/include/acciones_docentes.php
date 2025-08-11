<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $res_busc_doc['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar Cargo</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">


          <div class="x_content">
            <br />
            <form role="form" action="operaciones/actualizar_docente.php"
              class="form-horizontal form-label-left input_mask" method="POST">
              <input type="hidden" name="id" value="<?php echo $res_busc_doc['id']; ?>">
              <input type="hidden" name="dni_a" value="<?php echo $res_busc_doc['dni']; ?>">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">DNI : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="number" class="form-control" name="dni" required="required" maxlength="8"
                    value="<?php echo $res_busc_doc['dni']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Apellidos y Nombres : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="nom_ap" required="required"
                    value="<?php echo $res_busc_doc['apellidos_nombres']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Condición Laboral : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="cond_laboral" value="<?php echo $res_busc_doc['cond_laboral']; ?>"
                    required="required">
                    <option></option>
                    <option value="CONTRATADO" <?php if ("CONTRATADO" == $res_busc_doc['cond_laboral']) {
                      echo "selected";
                    } ?>>CONTRATADO</option>
                    <option value="NOMBRADO" <?php if ("NOMBRADO" == $res_busc_doc['cond_laboral']) {
                      echo "selected";
                    } ?>>
                      NOMBRADO</option>
                  </select>
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="date" class="form-control" name="fecha_nac" required="required"
                    value="<?php echo $res_busc_doc['fecha_nac']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"> Profesión : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="profesion" required="required"
                    style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"
                    value="<?php echo $res_busc_doc['profesion']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nivel de Formación : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="niv_formacion" required="required"
                    style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"
                    value="<?php echo $res_busc_doc['nivel_educacion']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="Number" class="form-control" name="telefono" required="required" maxlength="15"
                    value="<?php echo $res_busc_doc['telefono']; ?>">
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="email" class="form-control" name="email" required="required"
                    value="<?php echo $res_busc_doc['correo']; ?>">
                  <br>

                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="direccion" required="required"
                    style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"
                    value="<?php echo $res_busc_doc['direccion']; ?>">
                  <br>

                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Género : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" id="genero" name="genero"
                    value="<?php echo $res_busc_doc['id_genero']; ?>" required="required">
                    <option></option>
                    <?php
                    $ejec_busc_gen = buscarGenero($conexion);
                    while ($res_busc_gen = mysqli_fetch_array($ejec_busc_gen)) {
                      $id_gen = $res_busc_gen['id'];
                      $gen = $res_busc_gen['genero'];
                      ?>
                      <option value="<?php echo $id_gen;
                      ?>" <?php if ($id_gen == $res_busc_doc['id_genero']) {
                        echo "selected";
                      } ?>>
                        <?php echo $gen; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargo : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" id="cargo" name="cargo" value="<?php echo $res_busc_doc['id_cargo']; ?>"
                    required="required">
                    <option></option>
                    <?php
                    $ejec_busc_car = buscarCargo($conexion);
                    while ($res__busc_car = mysqli_fetch_array($ejec_busc_car)) {
                      $id_car = $res__busc_car['id'];
                      $car = $res__busc_car['descripcion'];
                      ?>
                      <option value="<?php echo $id_car;
                      ?>" <?php if ($id_car == $res_busc_doc['id_cargo']) {
                        echo "selected";
                      } ?>>
                        <?php echo $car; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Programa de Estudios : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" id="pe" name="pe" value="" required="required">
                    <option></option>
                    <?php
                    $b_busc_car = buscarCarreras($conexion);
                    while ($res_b_busc_car = mysqli_fetch_array($b_busc_car)) {
                      $id_pe = $res_b_busc_car['id'];
                      $pe = $res_b_busc_car['nombre'];
                      ?>
                      <option value="<?php echo $id_pe; ?>" <?php if ($id_pe == $res_busc_doc['id_programa_estudio']) {
                          echo "selected";
                        } ?>><?php echo $pe.' - '.$res_b_busc_car['plan_estudio']; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  <br>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Activo : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" id="activo" name="activo" value="" required="required">
                    <option></option>
                    <option value="1" <?php if ($res_busc_doc['activo'] == 1) {
                      echo "selected";
                    } ?>>SI</option>
                    <option value="0" <?php if ($res_busc_doc['activo'] == 0) {
                      echo "selected";
                    } ?>>NO</option>
                  </select>
                  <br>
                </div>
              </div>
              <div id="carga_academica_container" class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Carga Académica : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" id="carga_academica" name="carga_academica" value="" required="required">
                    <option></option>
                    <option value="1" <?php if ($res_busc_doc['carga_academica'] == 1) {
                      echo "selected";
                    } ?>>SI</option>
                    <option value="0" <?php if ($res_busc_doc['carga_academica'] == 0) {
                      echo "selected";
                    } ?>>NO</option>
                  </select>
                  <br>
                </div>
              </div>
              <div align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
        <!--FIN DE CONTENIDO DE MODAL-->
      </div>
    </div>
  </div>
</div>

