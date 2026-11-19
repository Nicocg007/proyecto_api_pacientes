<?php
/**
 * Conexión a la base de datos MySQL mediante PDO
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/cors.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
} catch (PDOException $e) {
    // Si la conexión falla, se retorna el código de error correspondiente
    responderJSON(false, 'No se pudo conectar a la base de datos', null, 500);
}
