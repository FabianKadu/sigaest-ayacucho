<?php

function normalizarTexto($texto) {
    // Eliminar tildes
    $texto = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
        $texto
    );

    // Reemplazar subguiones y otros caracteres especiales con espacios
    $texto = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $texto);

    // Convertir el texto a minúsculas
    $texto = strtolower($texto);

    // Eliminar espacios en blanco adicionales
    $texto = preg_replace('/\s+/', ' ', $texto);

    // Eliminar espacios en blanco al principio y al final del texto
    $texto = trim($texto);

    return $texto;
}

function calcularPorcentajeSimilitud($texto1, $texto2) {

    // Obtener la longitud de los textos normalizados
    
    $texto1 = normalizarTexto($texto1);
    $texto2 = normalizarTexto($texto2);
    
    // Convertir los textos en conjuntos de palabras únicas
    $palabras1 = array_unique(preg_split('/\s+/', $texto1));
    $palabras2 = array_unique(preg_split('/\s+/', $texto2));

    // Calcular el número de palabras comunes entre los dos textos
    $interseccion = count(array_intersect($palabras1, $palabras2));

    // Calcular el número total de palabras en ambos textos
    $union = count($palabras1) + count($palabras2) - $interseccion;

    // Calcular el coeficiente de Jaccard y convertirlo a porcentaje
    $porcentajeSimilitud = ($union == 0) ? 0 : ($interseccion / $union) * 100;

    return $porcentajeSimilitud;
}

// Ejemplo de uso
$texto1 = "GESTIÓN DE REDES";
$texto2 = "ADMINISTRACIÓN DE REDES";
$porcentaje = calcularPorcentajeSimilitud($texto1, $texto2);
echo "El porcentaje de similitud sin normalizar es : " . $porcentaje . " de cohencidencias";

?>