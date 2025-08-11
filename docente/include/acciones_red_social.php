<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $red_social['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-mg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar Red Social</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">

          <div class="x_content">
            <form role="form" action="operaciones/actualizar_red_social.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $red_social['id']; ?>">
              <div class="form-group">
                <label class="control-label">Red social: </label>
                <div class="">
                  <input type="text" class="form-control" name="nombre_red_social" value="<?php echo $red_social['nombre_red_social']; ?>" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">Nombre de usuario: </label>
                <div class="">
                  <input type="text" class="form-control" name="nombre_usuario" value="<?php echo $red_social['nombre_usuario'] ?>" required="required">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label ">Link: </label>
                <div class="">
                  <input type="text" class="form-control" name="link" required="required" value="<?php echo $red_social['link']; ?>">
                </div>
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