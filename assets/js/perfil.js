document.addEventListener('DOMContentLoaded', function() {
    const formPerfil = document.getElementById('formPerfil');
    const previewFoto = document.getElementById('previewFoto');
    const inputFoto = document.getElementById('foto');
    const msgPerfil = document.getElementById('msgPerfil');

    // Función para validar que solo contenga letras y espacios
    function validarNombreApellido(valor) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        return regex.test(valor.trim());
    }

    // Preview de la foto
    if (inputFoto) {
        inputFoto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('La imagen no debe superar 2MB');
                    this.value = '';
                    return;
                }
                
                if (!['image/jpeg', 'image/png'].includes(file.type)) {
                    alert('Solo se permiten imágenes JPG y PNG');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFoto.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }        });
    }

    // Envío del formulario
    if (formPerfil) {
        formPerfil.onsubmit = async function(e) {
            e.preventDefault();
            
            try {                // Verificar si se realizaron cambios
                const formData = new FormData(this);
                const nombre = formData.get('nombre');
                const apellido = formData.get('apellido');
                const correo = formData.get('correo');
                const clave = formData.get('clave');
                const foto = formData.get('foto');

                // Obtener los valores originales
                const nombreOriginal = this.querySelector('#nombre').defaultValue;
                const apellidoOriginal = this.querySelector('#apellido').defaultValue;
                const correoOriginal = this.querySelector('#correo').defaultValue;

                // Verificar si se modificó algún campo
                if (nombre !== nombreOriginal || apellido !== apellidoOriginal || 
                    correo !== correoOriginal || clave || foto.size > 0) {
                      // Validar que los campos modificados no estén vacíos y solo contengan letras
                    if (nombre !== nombreOriginal) {
                        if (!nombre.trim()) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Campo vacío',
                                text: 'El nombre no puede quedar vacío'
                            });
                            return;
                        }
                        if (!validarNombreApellido(nombre)) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Formato inválido',
                                text: 'El nombre solo puede contener letras y espacios'
                            });
                            return;
                        }
                    }
                    
                    if (apellido !== apellidoOriginal && apellido.trim()) {
                        if (!validarNombreApellido(apellido)) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Formato inválido',
                                text: 'El apellido solo puede contener letras y espacios'
                            });
                            return;
                        }
                    }
                    if (correo !== correoOriginal && !correo.trim()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campo vacío',
                            text: 'El correo no puede quedar vacío'
                        });
                        return;
                    }

                    // Solicitar confirmación con contraseña actual
                    const { value: passwordConfirm } = await Swal.fire({
                        title: 'Confirmar cambios',
                        input: 'password',
                        inputLabel: 'Por favor, ingresa tu contraseña actual para confirmar los cambios',
                        inputPlaceholder: 'Contraseña actual',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Necesitas ingresar tu contraseña para confirmar los cambios';
                            }
                        }
                    });

                    if (!passwordConfirm) {
                        return;
                    }

                    // Agregar la contraseña de confirmación al formData
                    formData.append('password_confirm', passwordConfirm);
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin cambios',
                        text: 'No se han realizado modificaciones en el perfil'
                    });
                    return;
                }

                // Mostrar loading
                Swal.fire({
                    title: 'Guardando cambios',
                    text: 'Por favor espere...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }                });
                
                const response = await fetch(base_url + 'clientes/actualizarPerfil', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                // Cerrar loading
                Swal.close();
                  if (data.icono === 'success') {
                    // Actualizar foto si se subió una nueva
                    if (data.foto) {
                        document.querySelectorAll('.img-thumbnail.rounded-circle').forEach(img => {
                            img.src = data.foto;
                        });
                    }
                    
                    // Actualizar nombre y correo en el perfil
                    const nombreCompleto = `${formData.get('nombre')} ${formData.get('apellido') || ''}`.trim();
                    const correo = formData.get('correo');
                    
                    const nombreElement = document.querySelector('.card-body h5');
                    const correoElement = document.querySelector('.card-body .text-muted');
                    
                    if (nombreElement) nombreElement.textContent = nombreCompleto;
                    if (correoElement) correoElement.innerHTML = `<i class="fas fa-envelope"></i> ${correo}`;

                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.msg,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    // Mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.msg
                    });
                }                } catch (error) {
                    console.error('Error detallado:', error);
                    
                    let errorMsg = 'Hubo un problema al actualizar el perfil';
                    
                    try {
                        const response = await fetch(base_url + 'clientes/actualizarPerfil', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();
                            if (data.msg) {
                                errorMsg = data.msg;
                            }
                        } else {
                            const text = await response.text();
                            console.error('Respuesta no JSON del servidor:', text);
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta:', e);
                    }

                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                        footer: 'Si el problema persiste, por favor contacta al soporte'
                    });
                }
        };
    }
});
