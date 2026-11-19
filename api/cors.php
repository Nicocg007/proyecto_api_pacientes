<?php
/**
 * Configuración de cabeceras CORS y Content-Type para la API
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Responder al preflight de CORS de navegadores modernos
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

/**
 * Función auxiliar para enviar respuestas JSON uniformes
 * Formato: { "exito": bool, "mensaje": string, "datos": mixed }
 */
function responderJSON($exito, $mensaje, $datos = null, $codigoHttp = 200) {
    http_response_code($codigoHttp);
    echo json_encode([
        'exito' => (bool)$exito,
        'mensaje' => (string)$mensaje,
        'datos' => $datos
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Función auxiliar para leer los datos recibidos (JSON o $_POST)
 */
function obtenerDatosEntrada() {
    $cuerpoCrudo = file_get_contents('php://input');
    $datos = json_decode($cuerpoCrudo, true);
    if (!is_array($datos) && !empty($_POST)) {
        $datos = $_POST;
    }
    return is_array($datos) ? $datos : [];
}
