<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $capacitaciones['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-mg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar Capacitación</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">

          <div class="x_content">
            <form role="form" action="operaciones/actualizar_capacitaciones.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $capacitaciones['id']; ?>">
              <input type="hidden" name="tipo" value="COMUNICADO">
              <div class="form-group">
                <label class="control-label">Tema de capacitación: </label>
                <div class="">
                  <input type="text" class="form-control" name="tema" required="" value="<?php echo $capacitaciones['tema']; ?>" style="text-transform:uppercase;">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">Descripción : </label>
                <div class="">
                  <textarea type="text" class="form-control" name="descripcion" required="required" rows="7"><?php echo $capacitaciones['descripcion'] ?></textarea>
                </div>
              </div>
              <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label class="control-label ">Fecha : </label>
                <div class="">
                  <input type="date" class="form-control" name="fecha" required="required" value="<?php echo $capacitaciones['fecha']; ?>">
                </div>
              </div>
              <div class="form-group col-md-3 col-sm-4 col-xs-12">
                <label class="control-label">¿Que hora? :
                </label>
                <div class="">
                  <input type="time" class="form-control" name="hora" id="hora" required="required" value="<?php echo $capacitaciones['hora']; ?>">
                </div>
              </div>
              <div class="form-group col-md-5 col-sm-4 col-xs-12">
                <label class="control-label">Duración:
                </label>
                <div class="" style="display: flex;">
                  <input class="form-control" type="number" id="duracion" name="duracion" min="1" required value="<?php echo $capacitaciones['duracion']; ?>">
                  <select class="form-control" id="unidad" name="unidad" required>
                    <option value="minutos">Minutos</option>
                    <option value="horas">Hora(s)</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">Ponentes: </label>
                <div class="">
                  <input type="text" class="form-control" name="ponentes" value="<?php echo $capacitaciones['ponentes']; ?>">
                </div>
              </div>
              <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">Enlace (Opcional) : </label>
                <div class="">
                  <input type="text" class="form-control" name="enlace" value="<?php echo $capacitaciones['enlace']; ?>">
                </div>
                <br>
              </div>
              <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label class="control-label">¿Quiénes deben de ver el capacitaciones? : </label>
                <div class="">

                  <?php
                  $cargos_selected = $capacitaciones['usuarios'];
                  $cargos_seleccionados = explode('-', $cargos_selected);
                  $res_cargos = buscarCargo($conexion);
                  while ($cargo = mysqli_fetch_array($res_cargos)) {
                  ?>
                    <label class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" name="cargo[]" value="<?php echo $cargo['id']; ?>"
                        <?php
                        if (in_array($cargo['id'], $cargos_seleccionados)) {
                          echo "checked";
                        }
                        ?>><?php echo $cargo['descripcion']; ?></label>
                  <?php } ?>
                  <label class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" name="cargo[]" value="<?php echo 0 ?>"
                      <?php
                      if (in_array(0, $cargos_seleccionados)) {
                        echo "checked";
                      }
                      ?>> ESTUDIANTES</label>
                </div>
                <br>
                <br>
              </div>
              <div align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="reset">Deshacer Cambios</button>
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