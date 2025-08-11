<div class="modal fade cerrar_<?php echo $res_busc_per_acad['id']; ?>" id="update_password" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updatePasswordModalLabel">Cerrar periodo academico</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="cerrarPeriodo<?php echo $res_busc_per_acad['id']; ?>" action="operaciones/cerrar_periodo.php" method="POST">
                    <input type="hidden" name="id_periodo" value="<?php echo $res_busc_per_acad['id']; ?>">
                    <input type="hidden" name="password_db" value="<?php echo $password ?>">
                    
                    <div class="form-group">
                        <label for="password<?php echo $res_busc_per_acad['id']; ?>">Ingrese contraseña.</label>
                        <input type="password" class="form-control" id="password<?php echo $res_busc_per_acad['id']; ?>" name="password" required>
                    </div>
                </form>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="cerrarPeriodo<?php echo $res_busc_per_acad['id']; ?>">Finalizar periodo</button>
            </div>
        </div>
    </div>
</div>
