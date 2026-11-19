-- =============================================================================
-- Base de Datos: apipacientes
-- Script de inicialización para hosting / MySQL / phpMyAdmin
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `apipacientes` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `apipacientes`;

-- Estructura de tabla para la tabla `patients`
CREATE TABLE IF NOT EXISTS `patients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `age` INT NOT NULL,
  `gender` ENUM('M', 'F', 'O') NOT NULL,
  `diagnosis` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo iniciales
INSERT INTO `patients` (`name`, `age`, `gender`, `diagnosis`, `created_at`) VALUES
('María Gómez', 28, 'F', 'Dolor abdominal', NOW()),
('Carlos Sánchez', 45, 'M', 'Hipertensión', NOW()),
('Laura Restrepo', 31, 'F', 'Control de rutina', NOW());
