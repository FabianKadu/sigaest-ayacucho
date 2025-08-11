<?php

  include "../include/conexion.php";
  include "../include/busquedas.php";
  include "../include/funciones.php";

  $res_procesos = buscarProcesosActivos($conexion);
  $contador = mysqli_num_rows($res_procesos);

  if ($contador === 0) {
    echo "<script>
                  alert('No hay procesos de admisión activos en este momento!');
                  window.history.back();
              </script>";
  } else{

    $proceso = mysqli_fetch_array($res_procesos);
    $proceso_admision = $proceso['Id'];
    $periodo = $proceso['Periodo'];
    $res_metodos_pago = buscarTodosMetodosPago($conexion);
    
    if($proceso['Tipo'] !== "ORDINARIO"){  
      $modalidades = buscarModalidadesPeriodo($conexion, $periodo);
    }
    $periodo = $proceso['Tipo']." ".$periodo;

    $estado = determinarEstadoAdmision($proceso['Inicio_Inscripcion'], $proceso['Fin_Inscripcion']);

    //AJUSTES DE ADMISION
    $res_ajustes_admision = buscarAjustesAdmisionPorIdProceso($conexion, $proceso_admision);

    //DETERMINAR EXTEMPORANERO
    $estado_extemporaneo = determinarEstadoAdmision($proceso['Inicio_Extemporaneo'], $proceso['Fin_Extemporaneo']);
    $es_extemporaneo = false;
    if($estado_extemporaneo === "En proceso"){
      $es_extemporaneo = true;
    }

    //RECUPERAR IMAGEN DE PORTAL
    $res_imagen = buscarImagenPublicitariaPorIdProceso($conexion, $proceso_admision);
    $imagen = mysqli_fetch_array($res_imagen);
    $imagen = $imagen['Archivo'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Admisión - Portal</title>
   
       <!--icono en el titulo-->
   <link rel="shortcut icon" href="../img/favicon.ico">
</head>
<style>
  body,
  html {
    margin: 0;
    padding: 0;
    height: 100%;
    background-color: #f3f3f3;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  a{
    text-decoration: none;
  }

  .container {
    width: 70%;
    margin: 0 auto;
  }

  .image-container {
    text-align: center;
  }

  .image-wrapper {
    padding: 10px;
    margin: 10px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    /* Sombras suaves */
  }

  .image-wrapper img {
    max-width: 100%;
    height: auto;
  }


  button {
  cursor: pointer;
  font-weight: 700;
  font-family: Helvetica,"sans-serif";
  transition: all .2s;
  padding: 13px 50px;
  border-radius: 100px;
  background: #d91c1c;
  border: 1px solid transparent;
  display: flex;
  align-items: center;
  font-size: 15px;
  color: #fff;
  }

  button:hover {
    background: #d91c1cdd;
  }

  button > svg {
    width: 34px;
    margin-left: 10px;
    transition: transform .3s ease-in-out;
  }

  button:hover svg {
    transform: translateX(5px);
  }

  button:active {
    transform: scale(0.95);
  }





  .e-card {
    background: transparent;
    box-shadow: 0px 8px 28px -9px rgba(0, 0, 0, 0.45);
    position: relative;
    width: 30%;
    height: 200px;
    margin: 10px;
    border-radius: 16px;
    overflow: hidden;
  }

  .wave {
    position: absolute;
    width: 540px;
    height: 700px;
    opacity: 0.6;
    left: 0;
    top: 0;
    margin-left: -50%;
    margin-top: -90%;
    background: linear-gradient(744deg, #af40ff, #5b42f3 60%, #00ddeb);
  }

  .icon {
    width: 3em;
    margin-top: -1em;
    padding-bottom: 1em;
  }

  .infotop {
    text-align: center;
    font-size: 16px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    color: rgb(255, 255, 255);
    font-weight: 600;
    padding: 10px;
  }

  .name {
    font-size: 14px;
    font-weight: 100;
    position: relative;
    top: 1em;
  }

  .wave:nth-child(2),
  .wave:nth-child(3) {
    top: 210px;
  }

  .playing .wave {
    border-radius: 40%;
    animation: wave 3000ms infinite linear;
  }

  .wave {
    border-radius: 40%;
    animation: wave 55s infinite linear;
  }

  .playing .wave:nth-child(2) {
    animation-duration: 4000ms;
  }

  .wave:nth-child(2) {
    animation-duration: 50s;
  }

  .playing .wave:nth-child(3) {
    animation-duration: 5000ms;
  }

  .wave:nth-child(3) {
    animation-duration: 45s;
  }

  .programas {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }

  .vacante_programa {
    font-size: 60px;
  }



  .metodos_pago {
    overflow: auto;
    display: flex;
    scroll-snap-type: x mandatory;
    width: 90%;
    margin: 0 auto;
    padding: 0 15px;
  }

  .card {
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 1px 10px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(7px);
    -webkit-backdrop-filter: blur(7px);
    border-radius: 10px;
    padding: 2rem;
    margin: 1rem;
    width: 100%;
  }

  .metodos_pago:hover> :not(:hover) {
    opacity: 0.9;
  }

  .metodos_pago .title {
    width: 100%;
    display: inline-block;
    word-break: break-all;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: center;
    font-weight: 600;
    font-size: 15px;
    margin: 1rem auto;
  }



  .modalidades {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
  }

  .modalidades .card {
    position: relative;
    display: flex;
    justify-content: start;
    cursor: pointer;
    <?php if($proceso['Tipo'] !== "ORDINARIO"){ ?>
      width: 35%;
    <?php } else { ?>
      width: 80%;
<?php } ?>
    padding: 2em;
    background: #FFF;
    box-shadow: 0 0 6px 0 rgba(32, 32, 36, 0.12);
    transition: all 0.35s ease;
  }

  .modalidades .card::before,
  .modalidades .card::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: #099ad4;
    height: 4px;
  }

  .modalidades .card::before {
    width: 0;
    opacity: 0;
    transition: opacity 0 ease, width 0 ease;
    transition-delay: 0.5s;
  }

  .modalidades .card::after {
    width: 100%;
    background: white;
    transition: width 0.5s ease;
  }

  .modalidades .card .content {
    width: auto;
    max-width: 100%;
  }

  .modalidades .card .logo {
    margin: 0 0 1em;
    width: 10.625em;
    transition: all 0.35s ease;
  }

  .modalidades .card .h6 {
    color: #099ad4;
    font-weight: 600;
    text-transform: uppercase;
    margin: 0;
    width: 100%;
    letter-spacing: 2px;
  }

  .modalidades .card .hover_content {
    overflow: hidden;
    max-height: 0;
    width: 100%;
    transform: translateY(1em);
    transition: all 0.55s ease;
  }


  .modalidades .card:hover {
    <?php if($proceso['Tipo'] !== "ORDINARIO"){ ?>
      width: 35%;
    <?php } else { ?>
      width: 80%;
    <?php } ?>
    box-shadow: 0 10px 20px 0 rgba(32, 32, 36, 0.12);
  }

  .modalidades .card:hover::before {
    width: 100%;
    opacity: 1;
    transition: opacity 0.5s ease, width 0.5s ease;
    transition-delay: 0;
  }

  .modalidades .card:hover::after {
    width: 0;
    opacity: 0;
    transition: width 0 ease;
  }

  .modalidades .card:hover .logo {
    margin-bottom: 0.5em;
  }

  .modalidades .card:hover .hover_content {
    max-height: 12em;
    transform: none;
  }

  @keyframes wave {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }


  @media screen and (max-width: 768px) {
    .container {
      width: 95%;
    }

    .image-wrapper {
      margin: 10px 0px;
    }

    .animated-button {
      padding: 4px 30px;
      border: 2px solid;
      font-size: 12px;
    }

    .e-card {
      width: 43%;
      height: 200px;
    }

    .wave {
      width: 700px;
      height: 700px;
    }

    .modalidades .card {
      width: 100%;
    }

    .modalidades .card:hover {
      width: 100%;
    }
    
    .modalidades .card:hover .hover_content {
    max-height: 18em;
    transform: none;
    }
    
  }


  table {
    width: 100%;
    background: white;
    margin-bottom: 1.25em;
    border: solid 1px #dddddd;
    border-collapse: collapse;
    border-spacing: 0;
  }

  table tr th,
  table tr td {
    padding: 0.5625em 0.625em;
    font-size: 0.875em;
    color: #222222;
    border: 1px solid #dddddd;
  }

  table tr th {
    background-color: #5b42f3;
    color: #fff;
    font-weight: 100;
  }

  table tr.even,
  table tr.alt,
  table tr:nth-of-type(even) {
    background: #f9f9f9;
  }
</style>

<body>
  <div class="container">
    <div class="image-container">
      <div class="image-wrapper">
          <?php $res_sistema = buscarDatosSistema($conexion);
            $sistema = mysqli_fetch_array($res_sistema);
            $logo = $sistema['logo'];
          ?>
          <img src="../<?php echo $logo; ?>" width="300px">
        <center>
          <h2>PROCESO DE ADMISIÓN <?php echo $periodo; if($es_extemporaneo) echo ' - EXTEMPORANEO' ?></h2>
        </center>
        <br>
        <img src="<?php echo $imagen; ?>"
          alt="Imagen publicitaria del proceso de admisión">
        <?php if($estado == "En proceso" or $es_extemporaneo){ ?>
          <br><br>
        <center>
          <a href="inscripcion.php">
            <button>
              <span>Inscribase aquí</span>
            </button>
          </a>
        </center>
        <br>
        <center>
          <a href="login_postulante/">
            <button style="background-color: #36aa4d;">
              <span>Módulo postulante</span>
            </button>
          </a>
        </center>
        <?php } ?>
          <br>
      </div>
    </div>
    <div class="image-wrapper">
      <center>
        <h3>Vacantes Ofrecidas para el Proceso de Admisión <?php echo $proceso['Periodo'] ?> </h3>
      </center>
      <div class="programas">
      <?php 
        $res_programas = buscarCarreras($conexion);
        while ($row = mysqli_fetch_array($res_programas)){
        ?>
        <div class="e-card playing">
          <div class="image"></div>

          <div class="wave"></div>
          <div class="wave"></div>
          <div class="wave"></div>


          <div class="infotop">
            <?php 
            $total_vacantes_programa = buscarTotalVacantesPorPeriodoPrograma($conexion, $proceso['Periodo'], $row['id']);
            $vacantes_programa = mysqli_fetch_array($total_vacantes_programa);
            
            ?>
            <b class="vacante_programa"><?php echo $vacantes_programa['total_vacante_programa']; ?></b><br>
            <?php echo $row['nombre']; ?>
          </div>
        </div>
        <?php } ?>
      </div>
      <br>
    </div>

    <div class="image-wrapper">
      <center>
        <h3>Modalidades, Requisitos y Precios</h3>
      </center>
      <section class="modalidades">
        <?php if($proceso['Tipo'] !== "ORDINARIO"){  ?>
          <div class="card">
          <div class="content">
            <div class="h6">Requisitos generales para todas las modalidades</div>
            <div class="hover_content">
              <ul>
              <?php 
                                            
              while ($ajustes_admision = mysqli_fetch_array($res_ajustes_admision)){
                  if($ajustes_admision['Id_Modalidad'] < 1){  
              ?>
              <li><?php echo $ajustes_admision['Descripcion']; ?></li>
              <?php }} ?>
              </ul>
            </div>
          </div>
        </div>
        <?php
          while($row = mysqli_fetch_array($modalidades)){ ?>
        <div class="card">
          <div class="content">
            <?php if($es_extemporaneo){ ?>
              <div class="h6"><?php echo $row['Descripcion'].':  S/.'.$row['Monto_Extemporaneo']; ?></div>
            <?php }else{ ?>
              <div class="h6"><?php echo $row['Descripcion'].':  S/.'.$row['Monto']; ?></div>
            <?php } ?>
            <div class="hover_content">
              <ul>
              <?php $res_req = buscarRequisitoPorIdModalidad($conexion, $row['Id']);
              $req = mysqli_fetch_array($res_req); ?>
              <li><?php echo $req['Descripcion']; ?></li>
              </ul>
            </div>
          </div>
        </div>
        <?php }} else{ ?>
          <div class="card">
          <div class="content">
            <?php $res_ordinario = buscarModalidadOrdinario($conexion);
            $ordinario = mysqli_fetch_array($res_ordinario);
            ?>
          <?php if($es_extemporaneo){ ?>
            <div class="h6"><?php echo $ordinario['Descripcion'].':  S/.'.$ordinario['Monto_Extemporaneo']?>
          </div>
          <?php }else{ ?>
            <div class="h6"><?php echo $ordinario['Descripcion'].':  S/.'.$ordinario['Monto']?>
          </div>
          <?php } ?>
            <div class="hover_content">
              <ul>
              <?php $res_req = buscarRequisitosGeneralesPorProceso($conexion, $proceso_admision);
              while($req = mysqli_fetch_array($res_req)) { ?>
              <li><?php echo $req['Descripcion']; ?></li> <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      <?php  } ?>
      </section>
    </div>

    <div class="image-wrapper">
      <center>
        <h3>Formas de Pago</h3>
      </center>
      <div class="metodos_pago">
        <?php while($row = mysqli_fetch_array($res_metodos_pago)){?>
        <div class="card">
          <p class="title">
            <?php echo $row['Metodo']; ?>
            <?php if ($row['Banco'] !== "No requiere") echo "- ".$row['Banco']; ?>
          </p>
        </div>
        <?php } ?>
      </div>
    </div>
    <div class="image-wrapper">
      <center>
        <h3>Cuadro de Vacantes</h3>
      </center>
      <?php 
        //MODALIDADES
        $res_modalidades = buscarModalidadPorPeriodo($conexion, $proceso['Periodo']);
        $modalidades_exonerados = mysqli_num_rows($res_modalidades);
        $modalidades_exonerados = $modalidades_exonerados - 1;

        //PROGRAMAS DE ESTUDIO
        $res_programas = buscarCarreras($conexion);
      ?>
      <div class="metodos_pago">
        <table class="cuadro_vacantes">
          <thead>
              <tr>
                <th scope="col" rowspan="2">
                  <center>PROGRAMAS DE ESTUDIO</center>
                </th>
                <th scope="col" rowspan="2">
                  <center>TOTAL VACANTES </center>
                </th>
                <th scope="col" colspan="<?php echo $modalidades_exonerados; ?>">
                  <center>VACANTES POR EXONERADOS</center>
                </th>
                <th scope="col" rowspan="2">
                  <center>
                    VACANTES POR EXAMEN ORDINARIO
                  </center>
                </th>
              </tr>
              <tr>
                <?php
                while ($modalidad = mysqli_fetch_array($res_modalidades)) {
                  if($modalidad['Descripcion'] == "Ordinario"){
                    continue;
                  }
                ?>
                  <th scope="col">
                    <center><?php echo $modalidad['Descripcion']; ?>
                    </center>
                  </th>
                <?php
                }
                ?>
            </tr>
          </thead>
          <tbody>
          <?php while ($programa = mysqli_fetch_array($res_programas)) { ?>
              <tr>
                  <td><?php echo $programa['nombre']; ?></td>
                      <?php 
                      //VACANTE DEFAULT
                      $total_vacantes_programa = buscarTotalVacantesPorPeriodoPrograma($conexion, $proceso['Periodo'], $programa['id']);
                      $vacantes_programa = mysqli_fetch_array($total_vacantes_programa);
                      $vacante_meta_default = $vacantes_programa['total_vacante_programa'];
                      ?>
                      <td><center><span><?php echo $vacante_meta_default; ?></span> </center></td>
                      <?php
                      //CUADRO DE VACANTES
                      $res_cuadro_vacante = buscarCuadroVacantesPorPeriodoPrograma($conexion, $proceso['Periodo'], $programa['id']);
                      while($cuadro_vacantes = mysqli_fetch_array($res_cuadro_vacante)){ ?>
                          <td><center>
                          <span><?php echo $cuadro_vacantes['Vacantes']; ?>  </span>
                         </center></td>
                      <?php } ?>
              </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      <br>
    </div>
  </div>
  <br>
  <br>
</body>

</html>

<?php } ?>