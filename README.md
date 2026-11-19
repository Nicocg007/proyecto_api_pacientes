# Proyecto: Consumo y API de Pacientes (Full Stack PHP)

Este proyecto implementa la solución completa del taller **"Consumir la API de Pacientes"** de acuerdo con la presentación institucional (`Consumir_API_Pacientes.pptx`).

Incluye tanto el **Front-end en JavaScript puro** como la **API REST Back-end en PHP + PDO + MySQL** lista para publicar en cualquier hosting (cPanel, Hostinger, XAMPP, etc.).

---

## 📁 Estructura del Proyecto

```text
proyecto_api_pacientes/
├── index.php           # Punto de entrada raíz para el hosting (redirige a front/)
├── database.sql        # Script SQL para crear la base de datos y la tabla 'patients'
├── api/                # Back-end: API REST en PHP
│   ├── config.php      # Configuración de credenciales de la base de datos MySQL
│   ├── conexion.php    # Conexión PDO con manejo de errores y UTF-8
│   ├── cors.php        # Encabezados CORS, preflight OPTIONS y helpers JSON
│   ├── listar.php      # GET: Devuelve todos los pacientes
│   ├── ver.php         # GET: Devuelve un paciente por ID (?id=...)
│   ├── crear.php       # POST: Crea un nuevo paciente
│   ├── actualizar.php  # POST: Actualiza los datos de un paciente existente
│   └── eliminar.php    # POST: Elimina un paciente por ID
└── front/              # Front-end: Interfaz de usuario
    ├── index.html      # Estructura semántica, formulario y tabla de pacientes
    ├── estilos.css     # Estilos y diseño con colores institucionales SENA
    └── app.js          # Consumo con fetch(), validaciones y manejo del DOM
```

---

## 🚀 Despliegue en un Hosting Web (cPanel / Hostinger / Apache)

### Paso 1: Subir los Archivos
Sube todo el contenido de este repositorio a la carpeta pública de tu hosting (habitualmente `public_html/` o en una subcarpeta como `public_html/pacientes/`).

### Paso 2: Crear la Base de Datos
1. Ingresa al panel de tu hosting (cPanel / hPanel) y abre **MySQL / phpMyAdmin**.
2. Crea una nueva base de datos (por ejemplo, `apipacientes`).
3. Ve a la pestaña **Importar** y selecciona el archivo [`database.sql`](database.sql).

### Paso 3: Configurar las Credenciales en PHP
Abre el archivo [`api/config.php`](api/config.php) y coloca las credenciales creadas en tu hosting:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tu_nombre_de_bd');
define('DB_USER', 'tu_usuario_de_bd');
define('DB_PASS', 'tu_contraseña_de_bd');
```

### Paso 4: Configurar la URL de la API en el Front
En [`front/app.js`](front/app.js), activa la URL relativa para que consuma tu propia API en el hosting:

```javascript
// Opción 2: API propia en PHP en el mismo hosting
const API = '../api';
```

---

## 💻 Ejecución Local en XAMPP

1. Inicia **Apache** y **MySQL** desde el Panel de Control de XAMPP.
2. Abre `http://localhost/phpmyadmin/` e importa el archivo `database.sql`.
3. Accede al proyecto desde tu navegador en:
   `http://localhost/talleres/proyecto_api_pacientes/`
