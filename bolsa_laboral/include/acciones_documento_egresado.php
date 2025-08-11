<head>
  <style>
    .upload-btn {
      display: inline-block;
      border: 2px solid #428bca;
      /* Color similar a btn-primary */
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      background: transparent;
      /* Color similar a btn-primary */
      color: #fff;
      /* Texto blanco */
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    .upload-btn:hover {
      background-color: #357ebd;
      /* Color similar a btn-primary al pasar el ratón */
    }

    .hidden {
      display: none;
    }
  </style>

</head>
<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $res_docs_egr['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel" align="center">Editar Documento</h4>
      </div>
      <div class="modal-body">
        <!--INICIO CONTENIDO DE MODAL-->
        <div class="x_panel">


          <div class="x_content">
            <br />
            <form role="form" action="operaciones/actualizar_documento_egresado.php"
              class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $res_docs_egr['id']; ?>">

              <!-- Descripción -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción *: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" class="form-control" name="descripcion" required="required"
                    value="<?php echo $res_docs_egr['descripcion']; ?>">
                  <br>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargar Documento *: </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" name="carga_documento" id="file-3" class="inputfile inputfile-3"
                    data-multiple-caption="{count} archivos seleccionados" multiple
                    accept=".pdf, .doc" />
                  <span>Archivo actual: <?php echo $res_docs_egr['archivo']; ?></span>
                  <br> <br>
                </div>
              </div>

              <div align="center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" type="reset">Deshacer Cambios</button>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
              </div>
            </form>

          </div>
        </div>
        <!--FIN DE CONTENIDO DE MODAL-->
      </div>
    </div>
  </div>
</div>