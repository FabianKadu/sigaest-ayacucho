<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $actividad['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar Datos de la Actividad</h4>
      </div>
      <div class="modal-body">

        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">

          <div class="" align="center">
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form role="form" action="operaciones/actualizar_actividad.php"
              class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">

              <input type="hidden" class="form-control" value="<?php echo $id_estudiante; ?>" name="id_estudiante"
                required="required">

              <input type="hidden" class="form-control" value="<?php echo $actividad['id']; ?>" name="id_actividad"
                required="required">

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="tipo" value="" id="tipo" required="required">
                    <option></option>
                    <option value="Evento" <?php if ($actividad['tipo'] == "Evento")
                      echo "selected"; ?>>Evento</option>
                    <option value="Voluntariado" <?php if ($actividad['tipo'] == "Voluntariado")
                      echo "selected"; ?>>
                      Voluntariado</option>
                    <option value="Profesional" <?php if ($actividad['tipo'] == "Profesional")
                      echo "selected"; ?>>
                      Profesional</option>
                    <option value="Academica" <?php if ($actividad['tipo'] == "Academica")
                      echo "selected"; ?>>
                      Academica</option>
                  </select>
                </div>
                <br><br />
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargo laboral: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="nombre_cargo"
                    value="<?php echo $actividad['nombre_cargo']; ?>" required="required">
                </div>
                <br><br />
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre organización: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="nombre_organizacion"
                    value="<?php echo $actividad['nombre_organizacion']; ?>" required="required">
                </div>
                <br><br />
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Lugar: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="lugar" value="<?php echo $actividad['lugar']; ?>"
                    required="required">
                </div>
                <br><br />
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea class="form-control" rows="3" style="width: 100%; height: 165px;" name="descripcion"
                    required="required"> <?php echo $actividad['descripcion']; ?> </textarea>
                  <br>
                </div>
              </div>

              <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12 ">Fecha inicio : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="date" class="form-control" value="<?php echo $actividad['fecha_inicio']; ?>"
                    name="fecha_inicio" required="required">
                  <br>
                </div>
              </div>

              <div class="form-group ">
                <label class="control-label col-md-3 col-sm-3 col-xs-12 ">Fecha Fin : </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="date" class="form-control" value="<?php echo $actividad['fecha_fin']; ?>"
                    name="fecha_fin" required="required">
                  <br><br />
                </div>
              </div>

              <div align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--FIN DE CONTENIDO DE MODAL-->