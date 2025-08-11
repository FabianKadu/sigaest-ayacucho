<?php 
    function reg_sesion_empresa($conexion, $id_empresa, $token)
    {
        $fecha_hora_inicio = date("Y-m-d H:i:s");
        $fecha_hora_fin = strtotime('+1 minute', strtotime($fecha_hora_inicio));
        $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);
    
        $insertar = "INSERT INTO sesion_empresa (id_empresa, fecha_hora_inicio, fecha_hora_fin, token) VALUES ('$id_empresa','$fecha_hora_inicio','$fecha_hora_fin','$token')";
        $ejecutar_insertar = mysqli_query($conexion, $insertar);
        if ($ejecutar_insertar) {
            //ultimo registro de sesion
            $id_sesion = mysqli_insert_id($conexion);
            return $id_sesion;
        } else {
            return 0;
        }
    }
    
    function sesion_si_activa_empresa($conexion, $id_sesion, $token)
    {
    
        $hora_actuals = date("Y-m-d H:i:s");
        $hora_actual = strtotime('-1 minute', strtotime($hora_actuals));
        $hora_actual = date("Y-m-d H:i:s", $hora_actual);
    
        $b_sesion = buscarSesionEmpresaLoginById($conexion, $id_sesion);
        $r_b_sesion = mysqli_fetch_array($b_sesion);
    
        $fecha_hora_fin_sesion = $r_b_sesion['fecha_hora_fin'];
        $fecha_hora_fin = strtotime('+5 hour', strtotime($fecha_hora_fin_sesion));
        $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);
    
        if ((password_verify($r_b_sesion['token'], $token)) && ($hora_actual <= $fecha_hora_fin)) {
            actualizar_sesion_empresa($conexion, $id_sesion);
            return true;
        } else {
            return false;
        }
    }
    
    function actualizar_sesion_empresa($conexion, $id_sesion)
    {
        $hora_actual = date("Y-m-d H:i:s");
        $nueva_fecha_hora_fin = strtotime('+1 minute', strtotime($hora_actual));
        $nueva_fecha_hora_fin = date("Y-m-d H:i:s", $nueva_fecha_hora_fin);
    
        $actualizar = "UPDATE sesion_empresa SET fecha_hora_fin='$nueva_fecha_hora_fin' WHERE id=$id_sesion";
        mysqli_query($conexion, $actualizar);
    }

    function buscar_empresa_sesion($conexion, $id_sesion, $token)
    {
        $b_sesion = buscarSesionEmpresaLoginById($conexion, $id_sesion);
        $r_b_sesion = mysqli_fetch_array($b_sesion);
        if (password_verify($r_b_sesion['token'], $token)) {
            return $r_b_sesion['id_empresa'];
        }
        return 0;
    }
?>