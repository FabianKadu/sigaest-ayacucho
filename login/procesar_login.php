<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

$secret_key = "Ideas$$2024"; // Reemplaza esto con tu propia clave secreta
$issuer_claim = "sigaest.com"; // El dominio de tu servidor
$audience_claim = "AUDIDEAS"; // La audiencia para el JWT
$issuedat_claim = time(); // Tiempo de emisión del token
$notbefore_claim = $issuedat_claim; // Tiempo antes del cual el token no es válido
$expire_claim = $issuedat_claim + 3600; // Tiempo de expiración (1 hora)

$usuario = $_POST['usuario'];
$pass = $_POST['password'];

// Primero, intenta encontrar al usuario como docente
$ejec_busc_docente = buscarDocenteByDni($conexion, $usuario);
$res_busc_docente = mysqli_fetch_array($ejec_busc_docente);
$cont_docente = mysqli_num_rows($ejec_busc_docente);

// Asegúrate de que la solicitud sea POST
if (($cont_docente == 1) && (password_verify($pass, $res_busc_docente['password']))) {

    // Usuario es un docente
    $id_docente = $res_busc_docente['id'];
    $cargo_docente = $res_busc_docente['id_cargo'];
    $buscar_periodo = buscarPresentePeriodoAcad($conexion);
    $res_b_periodo = mysqli_fetch_array($buscar_periodo);
    $presente_periodo = $res_b_periodo['id_periodo_acad'];

    if ($res_busc_docente['activo'] != 1) {
        echo "<script>
                alert('Error, Usted no se encuentra activo en el sistema, Por Favor Contacte con el Administrador');
                history.back();
              </script>";
    } else {

        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id_docente" => $id_docente,
                "id_cargo" => $cargo_docente
            )
        );

        // Genera el token JWT
        $jwt = JWT::encode($token, $secret_key, 'HS256');

        session_start();

        if ($cargo_docente != 0) {
            $id_sesion = reg_sesion($conexion, $id_docente, $jwt);
            if ($id_sesion != 0) {
                $token = password_hash($jwt, PASSWORD_DEFAULT);
                $_SESSION['id_sesion'] = $id_sesion;
                $_SESSION['periodo'] = $presente_periodo;
                $_SESSION['token'] = $token;
                echo "
                <script> 
                    localStorage.setItem('authToken', '" . addslashes($jwt) . "');
                    window.location.replace('../docente/index.php'); 
                </script>";
            } else {
                echo "<script>
                    alert('Error al Iniciar Sesión. Intente Nuevamente');
                    //window.location.replace('../login/');
                    </script>";
            }
        } else {
            echo "<script>
                alert('Error en cargo, contacte administrador');
                history.back();
              </script>";
        }

    }
}
else {
    // Si no es docente, intenta encontrar al usuario como estudiante
    $ejec_busc_estudiante = buscarEstudianteByDni($conexion, $usuario);
    $res_busc_estudiante = mysqli_fetch_array($ejec_busc_estudiante);
    $cont_estudiante = mysqli_num_rows($ejec_busc_estudiante);

    if (($cont_estudiante == 1) && (password_verify($pass, $res_busc_estudiante['password']))) {
        // Usuario es un estudiante
        $id_estudiante = $res_busc_estudiante['id'];
        $buscar_periodo = buscarPresentePeriodoAcad($conexion);
        $res_b_periodo = mysqli_fetch_array($buscar_periodo);
        $presente_periodo = $res_b_periodo['id_periodo_acad'];

        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id_estudiante" => $id_estudiante,
                "rol" =>  "estudiante/egresado"
            )
        );

        // Genera el token JWT
        $jwt = JWT::encode($token, $secret_key, 'HS256');

        session_start();

        $id_sesion = reg_sesion_estudiante($conexion, $id_estudiante, $jwt);
        if ($id_sesion != 0) {
            $token = password_hash($jwt, PASSWORD_DEFAULT);
            $_SESSION['id_sesion_est'] = $id_sesion;
            $_SESSION['periodo'] = $presente_periodo;
            $_SESSION['token'] = $token;
            
            echo "
            <script> 
                localStorage.setItem('authToken', '" . addslashes($jwt) . "');
                window.location.replace('../estudiante/index.php'); 
            </script>";
        } else {
            echo "<script>
                    alert('Error al Iniciar Sesión. Intente Nuevamente');
                    history.back();
                </script>";
        }
    } else {
        // Usuario o contraseña incorrectos
        echo "<script>
                alert('Usuario o Contraseña incorrecto');
                history.back();
              </script>";
    }
}
mysqli_close($conexion);
?>
