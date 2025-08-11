<style>
  .responsive_img {
    padding: 10px;
    width: 100%;
    object-fit: cover;
  }

  .cargo {
    color: #fff;
  }
</style>

<style>
  .new-label {
    display: inline-block;
    background-color: green;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 5px;
    font-size: smaller;
  }
</style>

<div class="col-md-3 left_col menu_fixed scroll-view">
  <div class="left_col">
    <!-- <div class="navbar nav_title" style="border: 0;">
      <a href="index.php" class=""><i class=""></i> <span>Biblioteca</span></a>
    </div>-->
    <?php
    $busc_user_sesion = buscarDocenteById($conexion, $id_docente_sesion);
    $res_b_u_sesion = mysqli_fetch_array($busc_user_sesion);
    $mostrar_menu = false;
    ?>
    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <center>
        <div class="">
          <?php
          $res_recursos = buscarRecursos($conexion);
          $recursos = mysqli_fetch_array($res_recursos);
          if (isset($recursos['img_sistema'])) {
            ?>
            <img class="responsive_img profile_img" src="<?php echo $recursos['img_sistema'] ?>" alt=""
              class="img_sistema">

          <?php } ?>
        </div>
        <div>
          <h6 class="green">Bienvenido,</h6>
          <h5 class="cargo">Responsable de Empleabilidad</h5>
        </div>
      </center>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Menú de Navegación</h3>
        <ul class="nav side-menu">
          <li><a href="../bolsa_laboral/"><i class="fa fa-home"></i>Inicio</a>
          </li>
          <li><a><i class="fa fa-users"></i> Relación de Usuarios <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <!-- <li class="sub_menu"><a href="estudiantes.php"> Estudiantes y Egresados</a></li> -->
              <li class="sub_menu"><a href="administrativos_docentes.php"> Administrativos y Docentes</a></li>
              <li class="sub_menu"><a href="estudiante.php"> Estudiantes</a></li>
              <li class="sub_menu"><a href="egresados.php"> Egresados</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-briefcase"></i> Bolsa Laboral <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="solicitudes.php">Solicitudes de Empresas</a></li>
              <li class="sub_menu"><a href="empresas.php">Empresas</a></li>
              <li class="sub_menu"><a href="convocatorias.php">Convocatorias</a></li>
              <li class="sub_menu"><a href="convocatorias_archivadas.php">Convocatorias Archivadas</a></li>
              <li class="sub_menu"><a href="convocatoria_reportes.php">Reportes</a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-gears"></i>Mantenimiento<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="datos.php">Datos Institucionales</a></li>
              <li><a href="programa_estudio.php">Programas de estudio</a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="fa fa-wrench"></i>Soporte<span class="fa fa-chevron-down"></span><span class="new-label">
              </span>
            </a>
            <ul class="nav child_menu">
              <li><a href="tickets_bolsa_laboral.php">Tickets </a></li>
              <li><a href="preguntas_frecuentes_bolsa_laboral.php">Preguntas Frecuentes </a>
              </li>
              <li><a href="manuales_videotutoriales_bolsa_laboral.php">Manuales y tutoriales </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </div>

  </div>
</div>
<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="../img/no-image.jpeg" alt=""><?php echo $res_b_u_sesion['apellidos_nombres']; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li>
              <a href="../docente/login/enviar_correo.php"> Cambiar mi contraseña</a>
            </li>
            <li><a href="../include/cerrar_sesion.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
          </ul>
        </li>


        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?php
            $res_cargo = buscarCargoById($conexion, $res_b_u_sesion['id_cargo']);
            $cargo = mysqli_fetch_array($res_cargo);
            echo $cargo['descripcion']; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu  pull-right">
            <?php if ($res_b_u_sesion['carga_academica'] == 1 || $res_b_u_sesion['id_cargo'] == 7) {
              $res_cargo = buscarCargoById($conexion, 5);
              $cargo = mysqli_fetch_array($res_cargo);
              ?>
              <li><a href="../docente/operaciones/actualizar_cargo.php?cargo=5"><?php echo $cargo['descripcion']; ?></a>
              </li>
            <?php }
            if ($res_b_u_sesion['id_cargo'] == 5) { ?>
              <li><a href="../docente/operaciones/actualizar_cargo.php?cargo=7"><?php echo $cargo['descripcion']; ?></a>
              </li>
            <?php } ?>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->