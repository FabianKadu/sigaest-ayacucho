<div class="modal fade edit_password<?php echo $res_busc_est['id']; ?>" id="update_password" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updatePasswordModalLabel">Actualizar Contraseña</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <form id="updatePasswordForm<?php echo $res_busc_est['id']; ?>" action="operaciones/cambiar_contraseña.php" method="POST">
                    <input type="hidden" name="id_estudiante" value="<?php echo $res_busc_est['id']; ?>">
                    
                    <div class="form-group">
                        <label for="newPassword<?php echo $res_busc_est['id']; ?>">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="newPassword<?php echo $res_busc_est['id']; ?>" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword<?php echo $res_busc_est['id']; ?>">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirmPassword<?php echo $res_busc_est['id']; ?>" name="confirm_password" required>
                    </div>
                </form>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="updatePasswordForm<?php echo $res_busc_est['id']; ?>">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Validación en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('updatePasswordForm<?php echo $res_busc_est['id']; ?>');
        const newPassword = document.getElementById('newPassword<?php echo $res_busc_est['id']; ?>');
        const confirmPassword = document.getElementById('confirmPassword<?php echo $res_busc_est['id']; ?>');

        form.addEventListener('submit', function(event) {
            if (newPassword.value !== confirmPassword.value) {
                alert('Las contraseñas no coinciden. Por favor, inténtelo nuevamente.');
                event.preventDefault(); // Evita el envío del formulario
            }
        });
    });
</script>
