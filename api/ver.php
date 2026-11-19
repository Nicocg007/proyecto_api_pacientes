<?php
/**
 * GET /api/ver.php?id={id}
 * Devuelve un paciente específico por su ID
 */

require_once __DIR__ . '/conexion.php';

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responderJSON(false, 'Este endpoint debe llamarse por GET', null, 405);
}

// Validar parámetro id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    responderJSON(false, 'Debe indicar un id valido', null, 400);
}

try {
    $stmt = $pdo->prepare("SELECT id, name, age, gender, diagnosis, created_at FROM patients WHERE id = ?");
    $stmt->execute([$id]);
    $paciente = $stmt->fetch();

    if (!$paciente) {
        responderJSON(false, 'Paciente no encontrado', null, 404);
    }

    $paciente['id'] = (int)$paciente['id'];
    $paciente['age'] = (int)$paciente['age'];

    responderJSON(true, 'Paciente encontrado', $paciente, 200);
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al buscar el paciente', null, 500);
}
