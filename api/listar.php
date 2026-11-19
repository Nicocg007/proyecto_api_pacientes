<?php
/**
 * GET /api/listar.php
 * Devuelve la lista completa de pacientes
 */

require_once __DIR__ . '/conexion.php';

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responderJSON(false, 'Este endpoint debe llamarse por GET', null, 405);
}

try {
    $stmt = $pdo->query("SELECT id, name, age, gender, diagnosis, created_at FROM patients ORDER BY id ASC");
    $pacientes = $stmt->fetchAll();

    // Formatear tipos de datos para que coincidan con la especificación (id y age como enteros)
    foreach ($pacientes as &$p) {
        $p['id'] = (int)$p['id'];
        $p['age'] = (int)$p['age'];
    }
    unset($p);

    responderJSON(true, 'Listado de pacientes', $pacientes, 200);
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al consultar los pacientes', null, 500);
}
