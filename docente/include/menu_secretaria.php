<style>
  @media (min-width: 900px) {
    .scroll-view::-webkit-scrollbar {
      width: 1px;
      /* Anchura del scrollbar */
    }

    .scroll-view::-webkit-scrollbar-thumb {
      background-color: #dddddd;
      /* Color del scrollbar */
    }

    .scroll-view {
      max-height: calc(100vh - 50px);
      /* Resta la altura del top navigation si lo tienes */
      overflow-y: auto;
      /* Agrega scroll vertical si el contenido excede la altura máxima */
    }
  }

  .new-label {
    display: inline-block;
    background-color: green;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 5px;
    font-size: smaller;
  }

  .responsive_img {
    padding: 2px;
    width: 100%;

    object-fit: cover;
  }

  .cargo {
    color: #fff;
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
    $b_m_per_act = buscarPresentePeriodoAcad($conexion);
    $r_b_m_per_act = mysqli_fetch_array($b_m_per_act);
    $id_per_act_m = $r_b_m_per_act['id_periodo_acad'];
    $mostrar_menu = false;
    $texto = "Administrador";
    if ($res_b_u_sesion['id_cargo'] == 9)
      $mostrar_menu = true;
    if ($res_b_u_sesion['id_cargo'] == 2)
      $texto = "Secretario Académico";
    if ($res_b_u_sesion['id_cargo'] == 8)
      $texto = "Gestor Pedagógico";
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
          <h5 class="cargo"><?php echo $texto ?></h5>
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
          <li><a href="../docente/"><i class="fa fa-home"></i>Inicio</a>
          </li>

          <li><a><i class="fa fa-list"></i> Asistencia <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../docente/control_asistencia.php">Control de asistencia</a></li>
              <li class="sub_menu"><a href="../docente/asistencia_personal.php">Asistencia de personal</a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-table"></i> Admisión <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="estadisticas.php">Estadísticas</a></li>
              <li><a href="../docente/requisitos.php">Requisitos Generales</a></li>
              <li><a href="../docente/modalidades.php">Modalidades</a></li>
              <li><a href="../docente/medios_pago.php">Medios de Pago</a></li>
              <li><a href="../docente/procesos_admision.php">Proceso de Admisión</a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-calendar"></i> Planificacion <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="periodo_academico.php">Periodos Académicos</a></li>
              <li><a href="../docente/presente_periodo.php">Datos del Presente Académico</a></li>
              <li><a href="../docente/programacion.php">Programación de Clases</a></li>
              <li><a href="../docente/solicitudes_unidad_didactica_adm.php">Solicitudes de programación</a></li>
            </ul>
          </li>


          <li><a><i class="fa fa-check-square-o"></i> Matrículas <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../docente/matriculas.php">Registro de Matrícula</a></li>
              <li class="sub_menu"><a href="../docente/configuracion_matricula.php">Configuración de Matrícula</a></li>
              <li class="sub_menu"><a href="../docente/solicitudes_matricula.php">Solicitudes de Matrícula</a></li>
              <li class="sub_menu"><a href="../docente/licencias.php">Licencias</a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-file"></i> Documentos <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../docente/certificado.php">Certificado de estudios</a></li>
              <li class="sub_menu"><a href="../docente/boleta_de_notas.php">Boleta de Notas</a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-users"></i> Usuarios <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../docente/administrativo_docente.php">Administrativos y Docentes</a></li>
              <li class="sub_menu"><a href="../docente/estudiante.php"> Estudiantes</a></li>
              <li class="sub_menu"><a href="../docente/egresados.php"> Egresados </a></li>
              <li class="sub_menu"><a href="../docente/importar_usuarios.php"> Importar usuarios </a></li>
            </ul>
          </li>

          <li><a><i class="fa fa-book"></i> Evaluación <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="calificaciones_unidades_didacticas.php">Registro de Evaluación</a></li>
              <li class="sub_menu"><a href="convalidaciones.php">Convalidaciones</a></li>
              <li class="sub_menu"><a href="efsrt_aperturado.php">EFSRT aperturados</a></li>
              <li class="sub_menu"><a href="efsrt.php">EFSRT</a></li>
            </ul>
          </li>
          <?php if ($res_b_u_sesion['id_cargo'] == 9) { ?>
            <li><a><i class="fa fa-dollar"></i>Caja <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li class="sub_menu"><a href="../caja/concepto_ingresos.php">Concepto-Ingresos</a></li>
                <li class="sub_menu"><a href="../caja/comprobantes.php">Comprobantes</a></li>
                <li class="sub_menu"><a href="../caja/movimientos.php">Movimientos</a></li>
                <li class="sub_menu"><a href="../caja/movimientos_anulados.php">Movimientos Anulados</a></li>
                <li class="sub_menu"><a href="../caja/reportes_movimientos.php">Reportes</a></li>
              </ul>
            </li>
            <li><a href="../docente/api.php"><i class="fa fa-code"></i> API</a>
            </li>
          <?php } ?>
          <li><a><i class="fa fa-briefcase"></i> Bolsa Laboral <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../bolsa_laboral/solicitudes.php">Solicitudes de Empresas</a></li>
              <li class="sub_menu"><a href="../bolsa_laboral/empresas.php">Empresas</a></li>
              <li class="sub_menu"><a href="../bolsa_laboral/convocatorias.php">Convocatorias</a></li>
              <li class="sub_menu"><a href="../bolsa_laboral/convocatorias_archivadas.php">Convocatorias Archivadas</a>
              </li>
              <li class="sub_menu"><a href="../bolsa_laboral/convocatoria_reportes.php">Reportes</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-mortar-board"></i>Plan de estudio<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="../docente/programa_estudio.php">Programas de estudio</a></li>
              <li><a href="../docente/modulo_formativo.php">Módulos Formativos</a></li>
              <li><a href="../docente/unidad_didactica.php">Unidades Didacticas</a></li>
              <li><a href="../docente/competencias.php">Competencias</a></li>
              <li><a href="../docente/capacidades.php">Capacidades</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-gears"></i>Mantenimiento<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="../docente/datos.php">Datos Institucionales</a></li>
              <li><a href="../docente/semestre.php">Semestres</a></li>
              <li><a href="../docente/cargo.php">Cargos</a></li>
              <li><a href="../docente/recursos.php">Recursos </a></li>
              <li><a href="../docente/sistema.php">Sistema</a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="fa fa-bullhorn"></i>Medios de Difusión<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="../docente/redes_sociales.php">Redes Sociales </a></li>
              <li><a href="../docente/anuncio.php">Anuncios </a></li>
              <li><a href="../docente/encuesta.php">Encuestas </a></li>
              <li><a href="../docente/capacitaciones.php">Capacitaciones </a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="fa fa-wrench"></i>Soporte<span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="../docente/tickets.php">Tickets </a></li>
              <li><a href="../docente/preguntas_frecuentes.php">Preguntas Frecuentes </a></li>
              <li><a href="../docente/manuales_videotutoriales.php">Manuales y tutoriales </a></li>
            </ul>
          </li>

          <li>
            <a>
              <i class="fa fa-shield"></i>Seguridad<span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li class="sub_menu"><a href="../docente/backup.php">Backup</a></li>
              <li class="sub_menu"><a href="../docente/auditoria.php">Auditoria</a></li>
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
              <a href="login/enviar_correo.php"> Cambiar mi contraseña</a>
            </li>
            <li><a href="../include/cerrar_sesion.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
          </ul>
        </li>
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?php
            $busc_per_id = buscarPeriodoAcadById($conexion, $_SESSION['periodo']);
            $res_busc_per_id = mysqli_fetch_array($busc_per_id);
            echo $res_busc_per_id['nombre']; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <?php
            $buscar_periodos = buscarPeriodoAcademicoInvert($conexion);
            while ($res_busc_periodos = mysqli_fetch_array($buscar_periodos)) {
            ?>
              <li><a
                  href="../docente/operaciones/actualizar_sesion_periodo.php?dato=<?php echo $res_busc_periodos['id']; ?>"><?php if ($res_busc_periodos['id'] == $id_per_act_m) {
                                                                                                                              echo "<b>";
                                                                                                                            } ?><?php echo $res_busc_periodos['nombre']; ?><?php if ($res_busc_periodos['id'] == $id_per_act_m) {
                                                                                                                                                                              echo "</b>";
                                                                                                                                                                            } ?></a>
              </li>
            <?php
            }
            ?>
          </ul>
        </li>

        <?php if ($res_b_u_sesion['id_cargo'] != 9) { ?>
          <li class="">
            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <?php
              $res_cargo = buscarCargoById($conexion, $res_b_u_sesion['id_cargo']);
              $cargo = mysqli_fetch_array($res_cargo);
              echo $cargo['descripcion']; ?>
              <span class=" fa fa-angle-down"></span>
            </a>
            <ul class="dropdown-menu  pull-right">
              <?php if ($res_b_u_sesion['carga_academica'] == 1 || $res_b_u_sesion['id_cargo'] == 2) {
                $res_cargo = buscarCargoById($conexion, 5);
                $cargo = mysqli_fetch_array($res_cargo);
              ?>
                <li><a href="../docente/operaciones/actualizar_cargo.php?cargo=5"><?php echo $cargo['descripcion']; ?></a>
                </li>
              <?php }
              if ($res_b_u_sesion['id_cargo'] == 5) { ?>
                <li><a href="../docente/operaciones/actualizar_cargo.php?cargo=2"><?php echo $cargo['descripcion']; ?></a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->