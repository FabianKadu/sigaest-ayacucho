<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $res_busc_matricula['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar ajuste de matricula</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">


          <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" action="operaciones/actualizar_configuracion_matricula.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $res_busc_matricula['id']; ?>">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <label for="periodo" class="control-label">Indicar periodo</label>
                    <select class="form-control" name="periodo" id="anio" required>
                      <?php
                      $busc_periodos = buscarPeriodoAcademico($conexion);
                      while ($res_busc_periodos = mysqli_fetch_array($busc_periodos)) {
                        if ($res_busc_periodos['id'] == $res_busc_matricula['id_periodo_acad']) {
                          echo "<option value='" . $res_busc_periodos['id'] . "' selected>" . $res_busc_periodos['nombre'] . "</option>";
                        } else {
                          echo "<option value='" . $res_busc_periodos['id'] . "'>" . $res_busc_periodos['nombre'] . "</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label for="creditos" class="control-label">Créditos permitidos</label>
                    <input type="number" class="form-control" name="creditos" id="creditos" placeholder="Créditos" value="<?php echo $res_busc_matricula['creditos']; ?>" required>
                  </div>
                </div>

                <br>

                <div class="row">
                  <div class="col-md-6">
                    <label for="fechaInicio" class="control-label">Fecha de inicio</label>
                    <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" value="<?php echo $res_busc_matricula['fecha_inicio']; ?>" required>
                  </div>

                  <div class="col-md-6">
                    <label for="ultimoDiaMatricula" class="control-label">Último día de matrícula</label>
                    <input type="date" class="form-control" name="ultimoDiaMatricula" id="ultimoDiaMatricula" value="<?php echo $res_busc_matricula['ultimo_dia_matricula']; ?>" required>
                  </div>
                </div>



              </div>

              <div class="modal-footer">
                <center>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  <button type="submit" class="btn btn-success">Guardar Configuración</button>
                </center>
              </div>
            </form>
          </div>
        </div>
        <!--FIN DE CONTENIDO DE MODAL-->
      </div>
    </div>
  </div>
</div>