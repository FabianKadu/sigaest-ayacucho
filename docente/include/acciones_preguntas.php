<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $preguntas['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Editar pregunta</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                                    
                  <div class="x_content">
                    <form role="form" action="operaciones/actualizar_preguntas.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                      
                      <input type="hidden" name="id" value="<?php echo $preguntas['id']; ?>">
                      <div class="form-group">
                        <label class="control-label">Pregunta : </label>
                        <div class="">
                          <input type="text" class="form-control" name="pregunta" required="" value="<?php echo $preguntas['pregunta']; ?>" style="text-transform:uppercase;">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label">Respuesta : </label>
                        <div class="">
                          <textarea type="text" class="form-control" name="respuesta" required="required" rows="7"><?php echo $preguntas['respuesta'] ?></textarea>
                        </div>
                      </div>
                      
                      <div class="form-group col-md-12 col-sm-12 col-xs-12">
                          <label class="control-label">¿Quiénes deben de ver el preguntas? : </label>
                          <div class="">
                            
                            <?php
                            $cargos_selected = $preguntas['roles'];
                            $cargos_seleccionados = explode('-', $cargos_selected);
                            $res_cargos = buscarCargo($conexion);
                            while($cargo = mysqli_fetch_array($res_cargos)){
                            ?>
                            <label class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" name="cargo[]" value="<?php echo $cargo['id'];?>"  
                            <?php 
                            if (in_array($cargo['id'], $cargos_seleccionados)) {
                              echo "checked";
                            }
                            ?>
                            ><?php echo $cargo['descripcion']; ?></label>
                            <?php } ?>
                            <label class="col-md-6 col-sm-6 col-xs-12"><input type="checkbox" name="cargo[]" value="<?php echo 0?>"  
                            <?php 
                            if (in_array(0, $cargos_seleccionados)) {
                              echo "checked";
                            }
                            ?>
                            > ESTUDIANTES</label>
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
