<!--MODAL REGISTRAR-->
<div class="modal fade edit_<?php echo $proceso['Id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-mg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" align="center">Nuevo Proceso de Admisión</h4>
            </div>
            <div class="modal-body">
                <!--INICIO CONTENIDO DE MODAL-->
                <div class="x_panel">
                    <div class="x_content">
                        <form role="form" action="operaciones/actualizar_proceso_admision.php"
                            class="form-vertical form-label-right input_mask formularioEdit" method="POST">
                            <input type="hidden" name="id" value="<?php echo $proceso['Id']; ?>">
                            <input type="hidden" name="periodo" value="<?php echo $proceso['Periodo']; ?>">
        
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Periodo *: </label>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <select class="form-control" name="periodo_anio" value="<?php echo substr($proceso['Periodo'],0,4); ?>"  required="required">
                                        <?php $anio = date("Y"); ?>
                                        <option value="<?php echo strval($anio-1) ?>"
                                        <?php if(strval($anio-1) == substr($proceso['Periodo'],0,4)){
                                          echo 'selected=""';};?>
                                        ><?php echo $anio-1 ?></option>
                                        <option value="<?php echo strval($anio) ?>"
                                        <?php if(strval($anio) == substr($proceso['Periodo'],0,4)){
                                          echo 'selected=""';};?>
                                        ><?php echo $anio ?></option>
                                        <option value="<?php echo strval($anio+1) ?>"
                                        <?php if(strval($anio+1) == substr($proceso['Periodo'],0,4)){
                                          echo 'selected=""';};?>
                                        ><?php echo $anio+1 ?></option>
                                    </select>
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <select class="form-control" name="periodo_unidad" value="<?php echo substr($proceso['Periodo'],5); ?>"  required="required">
                                       
                                        <option value="I"
                                        <?php if("I" == substr($proceso['Periodo'],5)){
                                          echo 'selected=""';};?>
                                        >I</option>
                                        <option value="II"
                                        <?php if("II" == substr($proceso['Periodo'],5)){
                                          echo 'selected=""';};?>
                                        >II</option>
                                        <option value="III"
                                        <?php if("III" == substr($proceso['Periodo'],5)){
                                          echo 'selected=""';};?>
                                        >III</option>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Tipo de Proceso *: </label>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control" name="tipo" value="<?php echo $proceso['Tipo']; ?>" required="required">
                                        <option value="ORDINARIO" <?php if("ORDINARIO" == $proceso['Tipo']){
                                echo 'selected=""';};?>>Ordinario</option>
                                        <option value="EXONERADO" <?php if("EXONERADO" == $proceso['Tipo']){
                                echo 'selected=""';};?>>Exonerado</option>
                                    </select>
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">Inicio de Proceso *: </label>
                                <div class="">
                                    <input type="date" class="form-control" name="inicio" id="inicio"
                                    value="<?php echo $proceso['Fecha_Inicio']; ?>" required="required" >
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Fin de Proceso *:
                                </label>
                                <div class="">
                                    <input type="date" class="form-control" name="fin" id="fin"
                                    value="<?php echo $proceso['Fecha_Fin']; ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">Inicio de Inscripciones *: </label>
                                <div class="">
                                    <input type="date" class="form-control" name="inicio_ins"
                                    value="<?php echo $proceso['Inicio_Inscripcion']; ?>" required="required" >
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Fin de Inscripciones *:
                                </label>
                                <div class="">
                                    <input type="date" class="form-control" name="fin_ins"
                                    value="<?php echo $proceso['Fin_Inscripcion']; ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">Inicio Extemporaneo : </label>
                                <div class="">
                                    <input type="date" class="form-control" name="inicio_ext" value="<?php echo $proceso['Inicio_Extemporaneo']; ?>">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Fin Extemporaneo :
                                </label>
                                <div class="">
                                    <input type="date" class="form-control" name="fin_ext" value="<?php echo $proceso['Fin_Extemporaneo']; ?>">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Fecha de Examen de Admisión *:
                                </label>
                                <div class="">
                                    <input type="datetime-local" class="form-control" name="fecha_examen" value="<?php echo $proceso['Fecha_Examen']; ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">Lugar de Examen de Admisión *:
                                </label>
                                <div class="">
                                    <input type="text" class="form-control" name="lugar_examen" value="<?php echo $proceso['Lugar_Examen']; ?>" required="required">
                                    <br>
                                </div>
                            </div>
                            <div class="form-group form-group col-md-12 col-sm-12 col-xs-12" align="center">
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