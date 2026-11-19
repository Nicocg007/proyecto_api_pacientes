<?php
/**
 * POST /api/actualizar.php
 * Actualiza los datos de un paciente existente
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
    responderJSON(false, 'Error interno al consultar existencia del paciente', null, 500);
}

$errores = [];

// Validación de Nombre
$name = isset($datos['name']) ? trim((string)$datos['name']) : '';
if ($name === '' || strlen($name) > 100) {
    $errores[] = 'El nombre es obligatorio';
}

// Validación de Edad
$age = isset($datos['age']) && is_numeric($datos['age']) ? (int)$datos['age'] : null;
if ($age === null || $age < 0 || $age > 150) {
    $errores[] = 'La edad debe ser un numero entero entre 0 y 150';
}

// Validación de Género
$gender = isset($datos['gender']) ? strtoupper(trim((string)$datos['gender'])) : '';
if (!in_array($gender, ['M', 'F', 'O'], true)) {
    $errores[] = 'El genero debe ser M, F u O';
}

// Diagnóstico (opcional)
$diagnosis = isset($datos['diagnosis']) ? trim((string)$datos['diagnosis']) : null;
if ($diagnosis === '') {
    $diagnosis = null;
}

// Si existen errores de validación, responder con HTTP 400
if (!empty($errores)) {
    responderJSON(false, 'Datos invalidos', $errores, 400);
}

try {
    $sql = "UPDATE patients SET name = ?, age = ?, gender = ?, diagnosis = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $age, $gender, $diagnosis, $id]);

    // Consultar el registro actualizado
    $stmt = $pdo->prepare("SELECT id, name, age, gender, diagnosis, created_at FROM patients WHERE id = ?");
    $stmt->execute([$id]);
    $pacienteActualizado = $stmt->fetch();

    $pacienteActualizado['id'] = (int)$pacienteActualizado['id'];
    $pacienteActualizado['age'] = (int)$pacienteActualizado['age'];

    responderJSON(true, 'Paciente actualizado', $pacienteActualizado, 200);
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al actualizar el paciente', null, 500);
}
