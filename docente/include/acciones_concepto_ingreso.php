<!--MODAL EDITAR-->
<div class="modal fade edit_<?php echo $conc_ingresos['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Editar Concepto de Ingreso</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  
                  <div class="x_content">
                    <br />
                    <form role="form" action="operaciones/actualizar_concepto_ingreso.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $conc_ingresos['id']; ?>">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Concepto : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="concepto" required="" value="<?php echo $conc_ingresos['concepto']; ?>" style="text-transform:uppercase;"  oninput="validateInputText(this, 40)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Monto : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="monto" required="" value="<?php echo $conc_ingresos['monto']; ?>" style="text-transform:uppercase;" oninput="validateInputNum(this, 8)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Código : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="codigo" required="" value="<?php echo $conc_ingresos['codigo']; ?>" style="text-transform:uppercase;" oninput="limitarTamanio(this, 3)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Unidad : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="unidad" required="" value="<?php echo $conc_ingresos['unidad']; ?>" style="text-transform:uppercase;"  oninput="validateInputText(this, 20)" onkeyup="javascript:this.value=this.value.toUpperCase();">
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
