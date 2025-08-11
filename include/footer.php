<?php
// Obtener los datos del sistema
$buscar = buscarDatosSistema($conexion);
$res = mysqli_fetch_array($buscar);

// Consultar las redes sociales registradas
$query_redes = "SELECT * FROM redes_sociales";
$resultado_redes = mysqli_query($conexion, $query_redes);
?>
<style>

  /* Colores específicos para cada red social */
  .facebook {
    background-color: #3b5998;
  }

  .whatsapp {
    background-color: #25D366;
  }

  .youtube {
    background-color: #FF0000;
  }

  .btn-social.instagram {
    background-color: #E4405F;
  }

  .btn-social.tiktok {
    background-color: #000000;
  }

  .btn-social.x {
    background-color: #1DA1F2;
  }

  .btn-social.linkedin {
    background-color: #0077B5;
  }

  /* Hover para todos los botones */
  .btn-social:hover {
    opacity: 0.8;
  }

  /* Ajustes para alinear el contenido en la misma línea */
  footer .row {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
<footer>
  <div class="row">
    <!-- Pie de página general -->
    <div class="col-md-6">
      <?php echo htmlspecialchars($res['pie_pagina']); ?>
    </div>

    <!-- Redes sociales -->
    <div class="col-md-6 text-right">
      <div class="social-buttons">
        <?php while ($red_social = mysqli_fetch_assoc($resultado_redes)) : ?>
          <a href="<?php echo htmlspecialchars($red_social['link']); ?>"
            target="_blank" data-toggle="tooltip" data-original-title="<?php echo $red_social['nombre_usuario'] ?>" data-placement="top" 
            class="btn btn-dark <?php echo strtolower($red_social['nombre_red_social']); ?>">
            <i class="fa <?php echo htmlspecialchars($red_social['icono_clase']); ?>"></i>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</footer>