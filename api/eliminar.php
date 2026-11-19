<?php
/**
 * POST /api/eliminar.php
 * Elimina un paciente por su ID
 */

require_once __DIR__ . '/conexion.php';

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJSON(false, 'Este endpoint debe llamarse por POST', null, 405);
}

$datos = obtenerDatosEntrada();

// Validación del ID
$id = isset($datos['id']) && is_numeric($datos['id']) ? (int)$datos['id'] : null;
if (!$id || $id < 1) {
    responderJSON(false, 'Debe indicar un id valido', null, 400);
}

// Verificar si el paciente existe
try {
    $checkStmt = $pdo->prepare("SELECT id FROM patients WHERE id = ?");
    $checkStmt->execute([$id]);
    if (!$checkStmt->fetch()) {
        responderJSON(false, 'Paciente no encontrado', null, 404);
    }
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al consultar el paciente', null, 500);
}

try {
    $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
    $stmt->execute([$id]);

    responderJSON(true, 'Paciente eliminado', null, 200);
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al eliminar el paciente', null, 500);
}
