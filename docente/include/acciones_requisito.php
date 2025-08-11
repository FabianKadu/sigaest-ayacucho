
 <!--MODAL EDITAR-->
 <div class="modal fade edit_<?php echo $res_busc_requisitos['Id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Editar Datos del Requisito</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  
                  <div class="x_content">
                    <br />
                    <form role="form" action="operaciones/actualizar_requisito.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $res_busc_requisitos['Id']; ?>">
                      <input type="hidden" name="id_modalidad" value="<?php echo $res_busc_requisitos['Id_Modalidad']; ?>">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Título : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="titulo" required="required" value="<?php echo $res_busc_requisitos['Titulo']; ?>"
                          <?php if($res_busc_requisitos['Titulo'] == "Fotografías") echo "readonly"; ?>>
                          <br>
                        </div>
                      </div>
                      <input type="hidden" name="tipo" value="<?php echo $res_busc_requisitos['Tipo']; ?>">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea class="form-control" rows="3" style="width: 100%; height: 165px;" name="descripcion" required="required"><?php echo $res_busc_requisitos['Descripcion']; ?></textarea>

                          <br>
                          <br>
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
