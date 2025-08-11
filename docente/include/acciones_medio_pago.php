<!--MODAL REGISTRAR-->
<div class="modal fade edit_<?php echo $medio_pago['Id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-mg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" align="center">Nuevo Medio de Pago</h4>
            </div>
            <div class="modal-body">
                <!--INICIO CONTENIDO DE MODAL-->
                <div class="x_panel">
                    <div class="x_content">
                        <form role="form" action="operaciones/actualizar_medio_pago.php"
                            class="form-vertical form-label-right input_mask" method="POST" id="formularioMedioPago">
                            <input type="hidden" name="id" value="<?php echo $medio_pago['Id']; ?>">
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Medio de Pago *: </label>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control" name="medio" value="<?php echo $medio_pago['Metodo']; ?>" id="medioPagoEditar" required="required">
                                        <option></option>
                                        <option value="Efectivo"
                                        <?php if("Efectivo" == $medio_pago['Metodo']){
                                          echo 'selected=""';};?>>Efectivo</option>
                                        <option value="Yape" <?php if("Yape" == $medio_pago['Metodo']){
                                          echo 'selected=""';};?>>Yape</option>
                                        <option value="Depósito" <?php if("Depósito" == $medio_pago['Metodo']){
                                          echo 'selected=""';};?>>Depósito</option>
                                        <option value="Transferencia Interbancaria" <?php if("Transferencia Interbancaria" == $medio_pago['Metodo']){
                                          echo 'selected=""';};?>>Transferencia Interbancaria</option>
                                        <option value="Plin" <?php if("Plin" == $medio_pago['Metodo']){
                                          echo 'selected=""';};?>>Plin</option>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Banco o Entidad*:
                                </label>
                                <div class="">
                                    <input type="text" class="form-control" name="banco" id="bancoEditar" value="<?php echo $medio_pago['Banco']; ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">Número de Cuenta *: </label>
                                <div class="">
                                    <input type="text" class="form-control" name="cuenta" id="cuentaEditar" value="<?php echo $medio_pago['Cuenta']; ?>" required="required" >
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label"> CCI :
                                </label>
                                <div class="">
                                    <input type="text" class="form-control" name="cci" id="cciEditar" value="<?php echo $medio_pago['CCI']; ?>">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">Nombre del Titular o Responsable*: </label>
                                <div class="">
                                    <input type="text" class="form-control" name="titular" value="<?php echo $medio_pago['Titular']; ?>" required="required">
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
        </div>
    </div>
</div>
<!--FIN MODAL-->