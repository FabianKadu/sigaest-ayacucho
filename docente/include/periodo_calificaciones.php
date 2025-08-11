<div class="modal fade calificaciones_<?php echo $res_busc_per_acad['id']; ?>" id="update_password" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updatePasswordModalLabel">Ajustes de registro de calificaciones</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="updatePeriodoForm<?php echo $res_busc_per_acad['id']; ?>" action="operaciones/fecha_fin_registro_evaluaciones.php" method="POST">
                    <input type="hidden" name="id_periodo" value="<?php echo $res_busc_per_acad['id']; ?>">
                    
                    <div class="form-group">
                        <label for="newDate<?php echo $res_busc_per_acad['id']; ?>">Ingrese el ultimo dia de registro de evaluación.</label>
                        <input type="date" class="form-control" id="newDate<?php echo $res_busc_per_acad['id']; ?>" name="date_evaluacion" required>
                    </div>
                </form>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="updatePeriodoForm<?php echo $res_busc_per_acad['id']; ?>">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
