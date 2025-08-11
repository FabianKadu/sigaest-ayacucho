<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $res_busc_ticket['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">DETALLES TICKET - CÓDIGO:
          <?php echo $res_busc_ticket['codigo']; ?>
        </h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">


          <div class="x_content">

            <form role="form" action="operaciones/actualizar_ticket.php"
              class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $res_busc_ticket['id']; ?>">

              <!-- 
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Usuario: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="usuario" id="tipo" required="required">
                    <option></option>
                    <option value="1" <?php if ($res_busc_ticket['usuario'] == 1)
                      echo 'selected'; ?>>DIRECTOR</option>
                    <option value="2" <?php if ($res_busc_ticket['usuario'] == 2)
                      echo 'selected'; ?>>SECRETARIO ACADEMICO
                    </option>
                    <option value="3" <?php if ($res_busc_ticket['usuario'] == 3)
                      echo 'selected'; ?>>JEFE DE UNIDAD
                      ACADEMICA</option>
                    <option value="4" <?php if ($res_busc_ticket['usuario'] == 4)
                      echo 'selected'; ?>>JEFE DE
                      AREA/COORDINADOR</option>
                    <option value="5" <?php if ($res_busc_ticket['usuario'] == 5)
                      echo 'selected'; ?>>DOCENTE</option>
                    <option value="6" <?php if ($res_busc_ticket['usuario'] == 6)
                      echo 'selected'; ?>>TESORERO</option>
                    <option value="7" <?php if ($res_busc_ticket['usuario'] == 7)
                      echo 'selected'; ?>>ESTUDIANTE</option>
                  </select>
                </div>
              </div>
              <br><br>


              -->
              <?php
              $fecha = date('Y-m-d', strtotime($res_busc_ticket['fecha_registro']));
              $hora = date('H:i', strtotime($res_busc_ticket['fecha_registro']));
              ?>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha :</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <p class="form-control" rows="3"><?php echo $fecha; ?></p>
                  <!-- <br><br /> -->
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Hora :</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <p class="form-control" rows="3"><?php echo $hora; ?></p>
                  <!-- <br><br /> -->
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción :</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <p class="form-control" rows="3" style="width: 100%; height: 165px;" id="descripcion"
                    name="descripcion" required="required"><?php echo $res_busc_ticket['descripcion']; ?></p>
                  <br><br />
                </div>
              </div>

              <h4 class="modal-title" id="myModalLabel" align="center">Capturas de pantalla:</h4>
              <br><br />
              <?php if (!empty($res_busc_ticket['imagen1'])): ?>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 1:</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <img src=" ../docente/soporte_imagenes/<?php echo $res_busc_ticket['imagen1']; ?>"
                      class="img-responsive" alt="Imagen 1">
                    <br><br />
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($res_busc_ticket['imagen2'])): ?>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 2:</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <img src=" ../docente/soporte_imagenes/<?php echo $res_busc_ticket['imagen2']; ?>"
                      class="img-responsive" alt="Imagen 2">
                    <br><br />
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($res_busc_ticket['imagen3'])): ?>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 3:</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <img src=" ../docente/soporte_imagenes/<?php echo $res_busc_ticket['imagen3']; ?>"
                      class="img-responsive" alt="Imagen 3">
                    <br><br />
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($res_busc_ticket['imagen4'])): ?>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 4:</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <img src=" ../docente/soporte_imagenes/<?php echo $res_busc_ticket['imagen4']; ?>"
                      class="img-responsive" alt="Imagen 4">
                    <br><br />
                  </div>
                </div>
              <?php endif; ?>

              <?php if (!empty($res_busc_ticket['imagen5'])): ?>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 5:</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <img src=" ../docente/soporte_imagenes/<?php echo $res_busc_ticket['imagen5']; ?>"
                      class="img-responsive" alt="Imagen 5">
                    <br><br />
                  </div>
                </div>
              <?php endif; ?>

              <!-- Repite lo anterior para las otras imágenes -->


              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Estado: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="estado" value="" id="tipo" required="required">
                    <option></option>
                    <option value="Pendiente de Atencion" <?php if ($res_busc_ticket['estado'] == 'Pendiente de Atencion')
                      echo 'selected'; ?>>Pendiente de Atencion</option>
                    <option value="En Proceso de Atencion" <?php if ($res_busc_ticket['estado'] == 'En Proceso de Atencion')
                      echo 'selected'; ?>>En Proceso de Atencion</option>
                    <option value="Finalizado" <?php if ($res_busc_ticket['estado'] == 'Finalizado')
                      echo 'selected'; ?>>Finalizado</option>

                  </select>
                  <br><br />
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Comentario :</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea class="form-control" rows="3" style="width: 100%; height: 165px;" name="comentario"
                    required="required"><?php echo $res_busc_ticket['comentario']; ?></textarea>
                  <br><br />
                </div>
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