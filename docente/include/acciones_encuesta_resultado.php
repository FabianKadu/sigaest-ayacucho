<!--MODAL EDITAR-->
<div class="modal fade resultado_<?php echo $anuncio['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-mg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Enlace de resultados</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">

          <div class="x_content">
            <form role="form" action="operaciones/actualizar_encuesta_resultado.php" class="form-horizontal form-label-left input_mask" method="POST" >
              <input type="hidden" name="id" value="<?php echo $anuncio['id']; ?>">
              <div class="form-group">
                <label class="control-label">Enlace de resultados: </label>
                <div class="">
                  <input type="text" class="form-control" name="enlace" required="" value="<?php echo $anuncio['resultado_encuesta']; ?>">
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