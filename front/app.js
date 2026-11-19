/**
 * Taller: Consumir la API de Pacientes con JavaScript fetch puro
 * Estudiante / Desarrollador Front-end
 * Fuente de verdad: Consumir_API_Pacientes.pptx
 */

// ==========================================================================
// Configuración de la API
// ==========================================================================
// Opción 1: API remota en producción (por defecto, funciona de inmediato)
const API = 'https://apipacientes.adsocartago.com/api';

// Opción 2: API propia en PHP en el mismo hosting (descomentar al subir al hosting con MySQL)
// const API = '../api';

// Opción 3: API en servidor local XAMPP
// const API = 'http://localhost/apipacientes/api';

// ==========================================================================
// Referencias al DOM
// ==========================================================================
const form = document.querySelector('#form');
const inputId = document.querySelector('#id');
const inputName = document.querySelector('#name');
const inputAge = document.querySelector('#age');
const inputGender = document.querySelector('#gender');
const inputDiagnosis = document.querySelector('#diagnosis');

const formTitulo = document.querySelector('#form-titulo');
const badgeModo = document.querySelector('#badge-modo');
const btnGuardar = document.querySelector('#btn-guardar');
const btnCancelar = document.querySelector('#btn-cancelar');
const btnRecargar = document.querySelector('#btn-recargar');

const mensajeEl = document.querySelector('#mensaje');
const tbody = document.querySelector('#tabla tbody');

// ==========================================================================
// Función 5 del reto: Mostrar mensajes y errores de validación
// ==========================================================================
/**
 * Muestra el mensaje devuelto por la API en la zona designada.
 * - Si exito es true: muestra el mensaje de éxito.
 * - Si datos es un arreglo: une los errores de validación con comas (código 400).
 * - En cualquier otro caso: muestra el mensaje general devuelto (404, 405, 500).
 * Asigna la clase CSS 'ok' o 'error' según el resultado.
 *
 * @param {Object} json Respuesta formateada de la API
 */
function mostrarMensaje(json) {
  if (!mensajeEl) return;

  if (json.exito) {
    mensajeEl.textContent = json.mensaje;
  } else if (Array.isArray(json.datos)) {
    // Errores de validación devueltos en forma de arreglo
    mensajeEl.textContent = json.datos.join(', ');
  } else {
    mensajeEl.textContent = json.mensaje || 'Ocurrió un error inesperado al procesar la solicitud.';
  }

  mensajeEl.className = json.exito ? 'ok' : 'error';
}

/**
 * Notifica errores de red, servidor caído o excepciones no controladas.
 * @param {Error|string} error 
 */
function mostrarErrorRed(error) {
  if (!mensajeEl) return;
  const texto = error && error.message ? error.message : String(error);
  mensajeEl.textContent = 'Error de conexión o red: ' + texto;
  mensajeEl.className = 'error';
}

// ==========================================================================
// Función 1 del reto: Listar pacientes con fetch (GET /listar.php)
// ==========================================================================
/**
 * Realiza la petición GET a /listar.php.
 * Valida la propiedad `exito` antes de retornar los datos.
 * @returns {Promise<Array>} Arreglo de pacientes o arreglo vacío en caso de error.
 */
async function listar() {
  try {
    const resp = await fetch(API + '/listar.php');
    const json = await resp.json();

    if (!json.exito) {
      mostrarMensaje(json);
      return [];
    }

    return json.datos || [];
  } catch (error) {
    mostrarErrorRed(error);
    return [];
  }
}

/**
 * Pinta los pacientes obtenidos en la tabla HTML.
 * Uso estricto de textContent para prevenir vulnerabilidades XSS.
 */
async function cargar() {
  try {
    const pacientes = await listar();
    tbody.innerHTML = '';

    if (!pacientes || pacientes.length === 0) {
      const tr = tbody.insertRow();
      const td = tr.insertCell();
      td.colSpan = 6;
      td.className = 'texto-vacio';
      td.textContent = 'No hay pacientes registrados en este momento.';
      return;
    }

    pacientes.forEach(function (p) {
      const tr = tbody.insertRow();

      // Inserción segura de datos con textContent
      tr.insertCell().textContent = p.id;
      tr.insertCell().textContent = p.name;
      tr.insertCell().textContent = p.age;
      tr.insertCell().textContent = p.gender;
      tr.insertCell().textContent = p.diagnosis || '-';

      // Celda de acciones con botones dinámicos
      const tdAcciones = tr.insertCell();
      tdAcciones.className = 'acciones-celda';

      // Botón Editar
      const btnEdit = document.createElement('button');
      btnEdit.type = 'button';
      btnEdit.textContent = 'Editar';
      btnEdit.className = 'btn-editar';
      btnEdit.addEventListener('click', function () {
        editar(p.id);
      });

      // Botón Eliminar
      const btnDel = document.createElement('button');
      btnDel.type = 'button';
      btnDel.textContent = 'Eliminar';
      btnDel.className = 'btn-eliminar';
      btnDel.addEventListener('click', function () {
        eliminar(p.id, p.name);
      });

      tdAcciones.appendChild(btnEdit);
      tdAcciones.appendChild(document.createTextNode(' '));
      tdAcciones.appendChild(btnDel);
    });
  } catch (error) {
    mostrarErrorRed(error);
  }
}

// ==========================================================================
// Función 4 del reto: Cargar paciente en el formulario (GET /ver.php?id=...)
// ==========================================================================
/**
 * Obtiene los detalles de un paciente y los carga en el formulario para edición.
 * @param {number|string} id Identificador del paciente
 */
async function editar(id) {
  try {
    const resp = await fetch(API + '/ver.php?id=' + encodeURIComponent(id));
    const json = await resp.json();

    if (!json.exito) {
      mostrarMensaje(json);
      return;
    }

    const paciente = json.datos;
    if (!paciente) {
      mostrarMensaje({ exito: false, mensaje: 'No se encontraron datos del paciente.' });
      return;
    }

    // Cargar datos en los inputs del formulario
    inputId.value = paciente.id;
    inputName.value = paciente.name || '';
    inputAge.value = paciente.age !== undefined ? paciente.age : '';
    inputGender.value = paciente.gender || '';
    inputDiagnosis.value = paciente.diagnosis || '';

    // Cambiar la interfaz al modo edición
    formTitulo.textContent = 'Editar Paciente (ID: ' + paciente.id + ')';
    btnGuardar.textContent = 'Actualizar Paciente';
    badgeModo.textContent = 'Edición';
    badgeModo.classList.add('edicion');
    btnCancelar.style.display = 'inline-flex';

    // Desplazar la vista suavemente hacia el formulario
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    inputName.focus();
  } catch (error) {
    mostrarErrorRed(error);
  }
}

/**
 * Restablece el formulario al modo de creación.
 */
function cancelarEdicion() {
  form.reset();
  inputId.value = '';

  formTitulo.textContent = 'Registrar Nuevo Paciente';
  btnGuardar.textContent = 'Crear Paciente';
  badgeModo.textContent = 'Creación';
  badgeModo.classList.remove('edicion');
  btnCancelar.style.display = 'none';
}

// ==========================================================================
// Función 2 y 4 del reto: Crear (POST /crear.php) o Actualizar (POST /actualizar.php)
// ==========================================================================
form.addEventListener('submit', async function (e) {
  e.preventDefault();

  // Extraer datos del formulario con Object.fromEntries
  const datos = Object.fromEntries(new FormData(form));

  // Convertir edad a número según requerimiento
  datos.age = Number(datos.age);

  // Determinar si es actualización o creación según la presencia del id
  const esEdicion = Boolean(datos.id && String(datos.id).trim() !== '');

  let url;
  if (esEdicion) {
    datos.id = Number(datos.id);
    url = API + '/actualizar.php';
  } else {
    delete datos.id;
    url = API + '/crear.php';
  }

  try {
    const resp = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datos)
    });

    const json = await resp.json();
    mostrarMensaje(json);

    // Si la operación fue exitosa, limpiar y recargar tabla
    if (json.exito) {
      form.reset();
      cancelarEdicion();
      cargar();
    }
  } catch (error) {
    mostrarErrorRed(error);
  }
});

// Evento para el botón de cancelar edición
btnCancelar.addEventListener('click', function () {
  cancelarEdicion();
  if (mensajeEl) {
    mensajeEl.textContent = 'Edición cancelada.';
    mensajeEl.className = 'ok';
  }
});

// Botón para recargar la lista manualmente
btnRecargar.addEventListener('click', function () {
  cargar();
});

// ==========================================================================
// Función 3 del reto: Eliminar paciente (POST /eliminar.php)
// ==========================================================================
/**
 * Elimina un paciente previa confirmación del usuario.
 * @param {number|string} id Identificador del paciente
 * @param {string} nombre Nombre del paciente para el diálogo de confirmación
 */
async function eliminar(id, nombre) {
  const confirmacion = window.confirm(
    '¿Está seguro de que desea eliminar al paciente "' + (nombre || 'ID: ' + id) + '"?'
  );

  if (!confirmacion) {
    return;
  }

  try {
    const resp = await fetch(API + '/eliminar.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: Number(id) })
    });

    const json = await resp.json();
    mostrarMensaje(json);

    if (json.exito) {
      // Si se estaba editando justamente este paciente, resetear el formulario
      if (inputId.value === String(id)) {
        cancelarEdicion();
      }
      cargar();
    }
  } catch (error) {
    mostrarErrorRed(error);
  }
}

// ==========================================================================
// Inicialización al cargar la página
// ==========================================================================
document.addEventListener('DOMContentLoaded', function () {
  cargar();
});
