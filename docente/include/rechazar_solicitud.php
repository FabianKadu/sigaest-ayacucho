<!--MODAL RECHAZAR-->
<div class="modal fade" id="rechazar_solicitud_<?php echo $res_busc_prog['id']; ?>" tabindex="-1" role="dialog"
  aria-hidden="true">
  <div class="modal-dialog modal-m">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Rechazar Solicitud</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">


          <div class="x_content" align="center">
            <h4>Esta rechazando la solicitud de <?php echo $res_busc_docente['apellidos_nombres'] ?> para asignarle el
              curso <?php echo $res_b_ud['descripcion'] ?>
            </h4>

            <br />
            <form method="post" action="./operaciones/rechazar_solicitud_programacion.php" style="display:inline;">
              <input type="hidden" name="id_solicitud" value="<?php echo $res_busc_prog['id']; ?>">
              <div align="center">
                <button class="btn btn-success" type="submit" name="action" value="aceptar">Aceptar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              </div>
            </form>
          </div>
        </div>
        <!--FIN DE CONTENIDO DE MODAL-->
      </div>
    </div>
  </div>
</div>