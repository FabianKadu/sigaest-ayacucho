
 <!--MODAL EDITAR-->
 <div class="modal fade edit_<?php echo $res_busc_per_acad['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Editar Datos del Periodo Académico</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  
                  <div class="x_content">
                    <br />
                    <form role="form" action="operaciones/actualizar_presente_periodo.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $res_busc_per_acad['id']; ?>">
                      
                      
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Periodo Académico : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" name="id_per_acad" value="<?php echo $res_busc_per_acad['id_periodo_acad']; ?>" required="">
                            <option></option>
                          <?php 
                            $ejec_busc_per_acad = buscarPeriodoAcademico($conexion);
                            while ($res_busc_per = mysqli_fetch_array($ejec_busc_per_acad)) {
                              $id_per = $res_busc_per['id'];
                              $per = $res_busc_per['nombre'];
                              ?>
                              <option value="<?php echo $id_per;
                              ?>"<?php if($id_per == $id_periodo){
                                echo 'selected=""';
                              };?>><?php echo $per; ?></option>
                            <?php
                            }
                            ?>
                          </select>
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
