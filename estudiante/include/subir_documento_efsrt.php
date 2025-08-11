<!--MODAL REGISTRAR-->
<div class="modal fade subir_documentos<?php echo $efsrt['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-mg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" align="center">Subir documentos EFSRT</h4>
            </div>
            <div class="modal-body">
                <!--INICIO CONTENIDO DE MODAL-->
                <div class="x_panel">
                    <div class="x_content">
                        <form role="form" action="operaciones/actualizar_documentos_efsrt.php " enctype="multipart/form-data"
                            class="form-vertical form-label-right input_mask" method="POST" id="formularioMedioPago">
                            <input type="hidden" name="id" value="<?php echo $efsrt['id']; ?>">
                            
                            <div class="form-group form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label"> Carta de presentación aprobada :
                                </label>
                                <div class="">
                                    <input type="file" class="form-control" name="carta_file" id="cciEditar" value="<?php echo $efsrt['carta_presentacion'] ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label ">Informe de EFSRT: </label>
                                <div class="">
                                    <input type="file" class="form-control" name="informe_file" value="<?php echo $efsrt['informe'] ?>"  required="required">
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