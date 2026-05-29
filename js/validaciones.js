/**
 * ═══════════════════════════════════════════════════════════════════════
 * FUNCIONES DE VALIDACIÓN DE INPUTS - PHP 8+ Compatible
 * ═══════════════════════════════════════════════════════════════════════
 * Validaciones client-side para formularios de concurso de ensayo
 */

/**
 * Permite solo letras, espacios y caracteres con acentos (ñ, á, é, etc.)
 * @param {Event} event - Evento del teclado
 * @returns {boolean}
 */
function soloLetras(event) {
    var key = event.keyCode || event.which;
    var tecla = String.fromCharCode(key);
    
    // Permitir teclas especiales (backspace, tab, enter, delete, flechas, etc.)
    var especiales = [8, 9, 13, 27, 37, 38, 39, 40, 46];
    if (especiales.indexOf(key) !== -1) {
        return true;
    }
    
    // Permitir Ctrl/Cmd + A, C, V, X (copiar, pegar, cortar)
    if ((event.ctrlKey || event.metaKey) && [65, 67, 86, 88].indexOf(key) !== -1) {
        return true;
    }
    
    // Validar que sea letra con acentos, ñ o espacio
    var patron = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]$/;
    if (!patron.test(tecla)) {
        event.preventDefault();
        return false;
    }
    
    return true;
}

/**
 * Permite solo números (0-9)
 * @param {Event} event - Evento del teclado
 * @returns {boolean}
 */
function soloNumeros(event) {
    var key = event.keyCode || event.which;
    
    // Permitir: backspace, delete, tab, escape, enter
    if ([8, 9, 13, 27, 46].indexOf(key) !== -1 ||
        // Permitir: Ctrl/Cmd+A, C, V, X
        ((event.ctrlKey || event.metaKey) && [65, 67, 86, 88].indexOf(key) !== -1) ||
        // Permitir: home, end, left, right, up, down
        (key >= 35 && key <= 40)) {
        return true;
    }
    
    // Asegurar que es un número (0-9)
    if ((event.shiftKey || (key < 48 || key > 57)) && (key < 96 || key > 105)) {
        event.preventDefault();
        return false;
    }
    
    return true;
}

/**
 * Limpia caracteres no permitidos en nombres (solo letras y espacios)
 * Se aplica en evento 'input' para limpiar texto pegado
 * @param {HTMLInputElement} input - Elemento input
 */
function limpiarSoloLetras(input) {
    input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
}

/**
 * Limpia caracteres no numéricos
 * Se aplica en evento 'input' para limpiar texto pegado
 * @param {HTMLInputElement} input - Elemento input
 * @param {number} maxLength - Longitud máxima (default: 12)
 */
function limpiarSoloNumeros(input, maxLength = 12) {
    input.value = input.value.replace(/[^0-9]/g, '').slice(0, maxLength);
}

/**
 * Validar formato de email
 * @param {string} email - Email a validar
 * @returns {boolean}
 */
function validarEmail(email) {
    var patron = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return patron.test(email);
}

/**
 * Validar teléfono (10-12 dígitos)
 * @param {string} telefono - Teléfono a validar
 * @returns {boolean}
 */
function validarTelefono(telefono) {
    var patron = /^[0-9]{10,12}$/;
    return patron.test(telefono);
}

/**
 * Valida el formato de la CURP.
 * @param {string} curp
 * @returns {boolean}
 */
function validarCurpFormato(curp) {
    var patron = /^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/;
    return patron.test(String(curp || '').toUpperCase());
}

/**
 * Aplica la mascara equivalente a:
 * $('#curp').mask('AAAA000000HHAASSAA', ...)
 * @param {HTMLInputElement} input
 */
function aplicarMascaraCurpInput(input) {
    if (!input) return;

    // Dos máscaras posibles
    var mascaras = [
        'AAAA000000HAASSS00', // termina en números
        'AAAA000000HAASSSA0',
        'AAAA000000HAASSSAA', // letra + letra
    ];

    var valor = String(input.value || '').toUpperCase().replace(/[^A-Z0-9]/g, '');

    function aplicarMascara(mascara, valor) {
        var salida = '';
        var indiceValor = 0;

        while (salida.length < mascara.length && indiceValor < valor.length) {
            var tipo = mascara.charAt(salida.length);
            var caracter = valor.charAt(indiceValor);
            var valido = false;

            if (tipo === 'A') {
                valido = /^[A-Z]$/.test(caracter);
            } else if (tipo === '0') {
                valido = /^[0-9]$/.test(caracter);
            } else if (tipo === 'H') {
                valido = /^[HM]$/.test(caracter);
            } else if (tipo === 'S') {
                valido = /^[A-Z]$/.test(caracter);
            }

            if (valido) {
                salida += caracter;
                indiceValor++;
            } else {
                indiceValor++;
            }
        }

        return salida;
    }

    // Probar ambas máscaras y elegir la que mejor encaje
    var resultados = mascaras.map(function(m) {
        return aplicarMascara(m, valor);
    });

    // Elegimos la más larga (la que mejor match hace)
    var mejor = resultados.sort(function(a, b) {
        return b.length - a.length;
    })[0];

    input.value = mejor.slice(0, 18);

    input.setCustomValidity(
        validarCurpFormato(input.value)
            ? ''
            : 'La CURP debe tener el formato correcto.'
    );

    if (input.value.length === 18 && validarCurpFormato(input.value)) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } else {
        input.classList.remove('is-valid');
        if (input.value.length > 0) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }
}

/**
 * Bloquea el guardado si la CURP no cumple el formato o tiene menos de 18 caracteres.
 * @returns {boolean}
 */
function validarCurpAntesDeGuardar() {
    var curpEl = document.getElementById('curp');
    if (!curpEl) return true;

    aplicarMascaraCurpInput(curpEl);

    var curp = curpEl.value.trim();

    // Verificar que tenga exactamente 18 caracteres
    if (curp.length < 18) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'CURP incompleta',
                text: 'La CURP debe tener exactamente 18 caracteres. Actualmente tiene ' + curp.length + '.',
                confirmButtonColor: '#4A9FD5'
            }).then(function () {
                curpEl.focus();
            });
        } else {
            alert('La CURP debe tener exactamente 18 caracteres. Actualmente tiene ' + curp.length + '.');
            curpEl.focus();
        }
        return false;
    }

    // Verificar que cumpla el formato
    if (!validarCurpFormato(curp)) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'CURP inválida',
                text: 'La CURP debe capturarse con el formato correcto alfanumérico de 18 caracteres.',
                confirmButtonColor: '#4A9FD5'
            }).then(function () {
                curpEl.focus();
            });
        } else {
            alert('La CURP debe capturarse con el formato correcto alfanumérico de 18 caracteres.');
            curpEl.focus();
        }
        return false;
    }

    return true;
}

/**
 * Inicializar validaciones en inputs específicos
 * Llamar esta función cuando el DOM esté listo
 */
function inicializarValidaciones() {
    // Aplicar validación a campos de nombres/apellidos
    var camposNombres = document.querySelectorAll('input[data-validar="letras"]');
    camposNombres.forEach(function(input) {
        if (!input.disabled && !input.readOnly) {
            input.addEventListener('keypress', soloLetras);
            input.addEventListener('input', function() {
                limpiarSoloLetras(this);
            });
        }
    });
    
    // Aplicar validación a campos de teléfono
    var camposTelefono = document.querySelectorAll('input[data-validar="numeros"]');
    camposTelefono.forEach(function(input) {
        if (!input.disabled && !input.readOnly) {
            input.addEventListener('keypress', soloNumeros);
            input.addEventListener('input', function() {
                var maxLength = parseInt(this.getAttribute('maxlength')) || 18;
                limpiarSoloNumeros(this, maxLength);
            });
        }
    });

    var curpEl = document.getElementById('curp');
    if (curpEl && !curpEl.disabled && !curpEl.readOnly) {
        curpEl.setAttribute('maxlength', '18');
        curpEl.setAttribute('minlength', '18');
        curpEl.setAttribute('placeholder', 'AAAA######HAASSS##');
        curpEl.addEventListener('input', function() {
            aplicarMascaraCurpInput(this);
        });
        curpEl.addEventListener('blur', function() {
            aplicarMascaraCurpInput(this);
        });
        aplicarMascaraCurpInput(curpEl);
    }
}

// Auto-inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarValidaciones);
} else {
    inicializarValidaciones();
}

window.validarCurpFormato = validarCurpFormato;
window.aplicarMascaraCurpInput = aplicarMascaraCurpInput;
window.validarCurpAntesDeGuardar = validarCurpAntesDeGuardar;
