<?php
/**
 * POST /api/crear.php
 * Crea un nuevo paciente en la base de datos
 */

require_once __DIR__ . '/conexion.php';

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJSON(false, 'Este endpoint debe llamarse por POST', null, 405);
}

$datos = obtenerDatosEntrada();
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
    $sql = "INSERT INTO patients (name, age, gender, diagnosis, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $age, $gender, $diagnosis]);
    $nuevoId = (int)$pdo->lastInsertId();

    // Consultar el registro recién creado
    $stmt = $pdo->prepare("SELECT id, name, age, gender, diagnosis, created_at FROM patients WHERE id = ?");
    $stmt->execute([$nuevoId]);
    $pacienteCreado = $stmt->fetch();

    $pacienteCreado['id'] = (int)$pacienteCreado['id'];
    $pacienteCreado['age'] = (int)$pacienteCreado['age'];

    responderJSON(true, 'Paciente creado', $pacienteCreado, 200);
} catch (PDOException $e) {
    responderJSON(false, 'Error interno al registrar el paciente', null, 500);
}
