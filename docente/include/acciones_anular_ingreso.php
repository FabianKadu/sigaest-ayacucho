
   <!--MODAL REGISTRAR ANLUAR-->
   <div class="modal fade anular_ingreso_<?php echo $ingresos['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Anulación Irreversible de Registro en Caja</h4>
        
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  <div class="" align="center">
                    <h2 ></h2>
                    <div class="clearfix"></div>
                    </div>
                          <p>
                         <b> La anulación del registro de ingreso en caja implica designar al usuario como el responsable, siendo una acción irreversible que afectará el estado financiero. Por favor, registré el motivo antes de proceder.
                          </b>  
                        </p>
                  <div class="x_content">
                  <form role="form" action="operaciones/anular_ingreso.php" class="form-horizontal form-label-left input_mask" method="POST" >
                  <input type="hidden" name="id" value="<?php echo $ingresos['id']; ?>">
                    <br />
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Describa el Motivo : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="motivo" value="" required="required" style="text-transform:uppercase;">
                          <br>
                        </div>
                      </div>
                      <div align="center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          
                          <button type="submit" class="btn btn-primary">Guardar</button>
                      </div>
                    </form>
                  </div>
                </div>
                </div>
                          <!--FIN DE CONTENIDO DE MODAL-->
                
      </div>
    </div>
  </div>
</div>

<!-- FIN MODAL ANULAR-->
