<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
          alert('Error: No tiene permisos para acceder a esta página');
          window.location.replace('index.php');
        </script>";
  exit();
}

$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Auditoria <?php include("../include/header_title.php"); ?></title>

  <link rel="shortcut icon" href="../img/favicon.ico">
  <!-- Bootstrap -->
  <link href="../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

  <style>
    .audit-card {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 20px;
      margin-bottom: 30px;
      transition: all 0.3s ease;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .audit-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .audit-icon {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .audit-title {
      font-size: 22px;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .audit-description {
      color: #666;
      margin-bottom: 20px;
    }

    .main-title {
      font-size: 36px;
      font-weight: 600;
      margin-bottom: 40px;
      text-align: center;
      color: #2A3F54;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("include/menu_secretaria.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="container">
          <h1 class="main-title">Auditoría</h1>

          <div class="row">
            <!-- Ingresos -->
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <div class="audit-card text-center">
                <i class="fa fa-money audit-icon text-primary"></i>
                <h3 class="audit-title">Ingresos</h3>
                <p class="audit-description">Control y seguimiento de todos los ingresos registrados en caja</p>

                <button
                  class="btn btn-primary"
                  onclick="location.href='operaciones/exportar_logs_auditoria_ingresos.php';">
                  <i class="fa fa-download"></i> Descargar logs en Excel
                </button>
              </div>
            </div>

            <!-- Egresos -->
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <div class="audit-card text-center">
                <i class="fa fa-credit-card audit-icon text-danger"></i>
                <h3 class="audit-title">Egresos</h3>
                <p class="audit-description">Registro y control de todos los egresos procesados en caja</p>

                <button
                  class="btn btn-danger"
                  onclick="location.href='operaciones/exportar_logs_auditoria_egresos.php';">
                  <i class="fa fa-download"></i> Descargar logs en Excel
                </button>
              </div>
            </div>

            <!-- Evaluaciones -->
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <div class="audit-card text-center">
                <i class="fa fa-file-text audit-icon text-success"></i>
                <h3 class="audit-title">Evaluaciones</h3>
                <p class="audit-description">Historial y seguimiento de evaluaciones académicas</p>

                <button
                  class="btn btn-success"
                  onclick="location.href='operaciones/exportar_logs_auditoria_criterio_evaluacion.php';">
                  <i class="fa fa-download"></i> Descargar logs en Excel
                </button>
              </div>
            </div>

            <!-- Convalidaciones -->
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <div class="audit-card text-center">
                <i class="fa fa-exchange audit-icon text-warning"></i>
                <h3 class="audit-title">Convalidaciones</h3>
                <p class="audit-description">Correspondiente a las convalidaciones internas y externas</p>

                <button
                  class="btn btn-warning"
                  onclick="location.href='operaciones/exportar_logs_auditoria_convalidaciones.php';">
                  <i class="fa fa-download"></i> Descargar logs en Excel
                </button>
              </div>
            </div>

            <!-- EFRST -->
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <div class="audit-card text-center">
                <i class="fa fa-briefcase audit-icon text-info"></i>
                <h3 class="audit-title">EFRST</h3>
                <p class="audit-description">Correspondiente a las evaluaciones formativas en situaciones reales de trabajo</p>

                <button
                  class="btn btn-info"
                  onclick="location.href='operaciones/exportar_logs_auditoria_efrst.php';">
                  <i class="fa fa-download"></i> Descargar logs en Excel
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <?php include("../include/footer.php"); ?>
    </div>
  </div>

  <!-- jQuery -->
  <script src="../Gentella/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../Gentella/build/js/custom.min.js"></script>
</body>

</html>