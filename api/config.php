<?php
/**
 * Configuración de la Base de Datos para el Hosting / Local
 * Ajusta estas credenciales según los datos de tu hosting (cPanel, Hostinger, phpMyAdmin, etc.)
 */

// Host de la base de datos (generalmente 'localhost' o '127.0.0.1')
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');

// Nombre de la base de datos creada en el hosting
define('DB_NAME', getenv('DB_NAME') ?: 'apipacientes');

// Usuario de la base de datos
define('DB_USER', getenv('DB_USER') ?: 'root');

// Contraseña de la base de datos (en XAMPP suele ser vacía por defecto)
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

// Charset
define('DB_CHARSET', 'utf8mb4');
