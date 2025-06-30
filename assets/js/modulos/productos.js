const frm = document.querySelector("#frmRegistro");
const btnAccion = document.querySelector("#btnAccion");
const containerGaleria = document.querySelector("#containerGaleria");
let tblProductos;

var firstTabEl = document.querySelector("#myTab li:last-child button");
var firstTab = new bootstrap.Tab(firstTabEl);

const modalGaleria = new bootstrap.Modal(
    document.getElementById("modalGaleria")
);

let desc;

const btnProcesar = document.querySelector("#btnProcesar");

document.addEventListener("DOMContentLoaded", function() {

    tblProductos = $("#tblProductos").DataTable({
        ajax: {
            url: base_url + "productos/listar",
            dataSrc: "",
        },
        columns: [
            { data: "id" },
            { data: "nombre" },
            { data: "precio" },
            { data: "cantidad" },
            { data: "imagen" },
            { data: "destacado", render: function(data){ return data == 1 ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>'; } },
            { data: "accion" },
        ],
        language,
        dom,
        buttons,
    });

    //submit productos
    frm.addEventListener("submit", function(e) {
        e.preventDefault();
        let data = new FormData(this);
        const url = base_url + "productos/registrar";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(data);
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res.icono == "success") {
                    frm.reset();
                    tblProductos.ajax.reload();
                    document.querySelector("#imagen").value = "";
                }
                Swal.fire("Aviso?", res.msg.toUpperCase(), res.icono);
            }
        };
    });

    //galeria de imagenes
    let myDropzone = new Dropzone(".dropzone", {
        dictDefaultMessage: "Arrastra y suelta imágenes aquí o haz clic para seleccionar",
        dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo: {{maxFilesize}}MB.",
        dictInvalidFileType: "No puedes subir archivos de este tipo. Solo JPG y PNG están permitidos.",
        dictResponseError: "Ha ocurrido un error en el servidor: {{statusCode}}",
        dictCancelUpload: "Cancelar subida",
        dictUploadCanceled: "Subida cancelada",
        dictRemoveFile: "Eliminar",
        acceptedFiles: "image/jpeg,image/png",
        maxFilesize: 5, // MB
        maxFiles: 10,
        addRemoveLinks: true,
        autoProcessQueue: false,
        parallelUploads: 1, // Procesar uno a la vez para mantener el orden numérico
        init: function() {
            this.on("addedfile", function(file) {
                // Mostrar previsualización mejorada
                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        file.previewElement.querySelector("img").src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
            
            this.on("success", function(file, response) {
                if (response && response.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error
                    });
                    this.removeFile(file);
                }
            });
            
            this.on("error", function(file, errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            });
        }
    });

    btnProcesar.addEventListener("click", function() {
        Swal.fire({
            title: 'Procesando imágenes',
            text: 'Las imágenes están siendo convertidas a WebP...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        myDropzone.processQueue();
    });

    myDropzone.on("queuecomplete", function() {
        Swal.fire({
            icon: 'success',
            title: '¡Completado!',
            text: 'Las imágenes han sido procesadas y convertidas a WebP'
        });
        
        setTimeout(() => {
            modalGaleria.hide();
            // Recargar la galería
            agregarImagenes(document.querySelector("#idProducto").value);
        }, 1500);
    });

    // --- COLORES DINÁMICOS ---
    const colorPicker = document.getElementById('colorPicker');
    const btnAgregarColor = document.getElementById('btnAgregarColor');
    const listaColores = document.getElementById('listaColores');
    const inputColores = document.getElementById('colores');
    const colorError = document.createElement('div');
    colorError.className = 'text-danger small mt-1';
    listaColores.parentNode.insertBefore(colorError, listaColores.nextSibling);
    let colores = [];

    function isValidHexColor(hex) {
        return /^#[0-9A-Fa-f]{6}$/.test(hex);
    }

    btnAgregarColor && btnAgregarColor.addEventListener('click', function() {
        const color = colorPicker.value;
        colorError.textContent = '';
        if (!isValidHexColor(color)) {
            colorError.textContent = 'Selecciona un color válido.';
            return;
        }
        if (colores.includes(color)) {
            colorError.textContent = 'Ese color ya está agregado.';
            return;
        }
        colores.push(color);
        renderColores();
    });
    function renderColores() {
        listaColores.innerHTML = '';
        colores.forEach(c => {
            const div = document.createElement('div');
            div.className = 'color-circle';
            div.style.background = c;
            div.style.width = '28px';
            div.style.height = '28px';
            div.style.border = '2px solid #00e1ff';
            div.style.cursor = 'pointer';
            div.title = c;
            div.onclick = function() {
                colores = colores.filter(col => col !== c);
                renderColores();
            };
            listaColores.appendChild(div);
        });
        inputColores.value = colores.join(',');
    }

    // --- TALLAS DINÁMICAS ---
    const inputTalla = document.getElementById('inputTalla');
    const btnAgregarTalla = document.getElementById('btnAgregarTalla');
    const listaTallas = document.getElementById('listaTallas');
    const inputTallas = document.getElementById('tallas');
    const tallaError = document.createElement('div');
    tallaError.className = 'text-danger small mt-1';
    listaTallas.parentNode.insertBefore(tallaError, listaTallas.nextSibling);
    let tallas = [];

    btnAgregarTalla && btnAgregarTalla.addEventListener('click', function() {
        const talla = inputTalla.value.trim();
        tallaError.textContent = '';
        if (!talla) {
            tallaError.textContent = 'La talla no puede estar vacía.';
            return;
        }
        if (talla.length < 1) {
            tallaError.textContent = 'La talla debe tener al menos 1 carácter.';
            return;
        }
        if (tallas.includes(talla)) {
            tallaError.textContent = 'Esa talla ya está agregada.';
            return;
        }
        tallas.push(talla);
        renderTallas();
        inputTalla.value = '';
    });
    function renderTallas() {
        listaTallas.innerHTML = '';
        tallas.forEach(t => {
            const span = document.createElement('span');
            span.className = 'badge bg-info text-dark fs-6 px-3 py-2 talla-badge';
            span.style.cursor = 'pointer';
            span.textContent = t;
            span.onclick = function() {
                tallas = tallas.filter(tal => tal !== t);
                renderTallas();
            };
            listaTallas.appendChild(span);
        });
        inputTallas.value = tallas.join(',');
    }

    // Validación al enviar el formulario
    frm.addEventListener('submit', function(e) {
        colorError.textContent = '';
        tallaError.textContent = '';
        if (colores.length === 0) {
            colorError.textContent = 'Agrega al menos un color.';
            e.preventDefault();
            return false;
        }
        if (tallas.length === 0) {
            tallaError.textContent = 'Agrega al menos una talla.';
            e.preventDefault();
            return false;
        }
        // Validar que todos los colores sean válidos
        for (let c of colores) {
            if (!isValidHexColor(c)) {
                colorError.textContent = 'Uno de los colores no es válido.';
                e.preventDefault();
                return false;
            }
        }
        // Validar que todas las tallas tengan al menos 1 carácter
        for (let t of tallas) {
            if (!t || t.length < 1) {
                tallaError.textContent = 'Todas las tallas deben tener al menos 1 carácter.';
                e.preventDefault();
                return false;
            }
        }
    });

    // Si se edita un producto, cargar colores y tallas existentes
    window.editPro = function(idPro) {
        const url = base_url + "productos/edit/" + idPro;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                document.querySelector("#id").value = res.id;
                document.querySelector("#nombre").value = res.nombre;
                document.querySelector("#precio").value = res.precio;
                document.querySelector("#cantidad").value = res.cantidad;
                document.querySelector("#categoria").value = res.id_categoria;
                document.querySelector("#descripcion").value = res.descripcion;
                document.querySelector("#imagen_actual").value = res.imagen;
                document.querySelector("#destacado").value = res.destacado;
                // Cargar colores, tallas y calificación si existen
                fetch(base_url + 'productos/atributos/' + res.id)
                    .then(r => r.json())
                    .then(atr => {
                        // Colores
                        colores = (atr.colores || []).map(c => c.color || c); // compatibilidad
                        renderColores();
                        // Tallas
                        tallas = (atr.tallas || []).map(t => t.talla || t);
                        renderTallas();
                        // Calificación
                        document.querySelector('#calificacion').value = (atr.calificacion && atr.calificacion.length > 0 ? atr.calificacion[0].calificacion : '');
                    });
                btnAccion.textContent = "Actualizar";
                firstTab.show();
            }
        };
    }

    function agregarImagenes(idPro) {
        const url = base_url + "productos/verGaleria/" + idPro;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                document.querySelector("#idProducto").value = idPro;
                let html = '';
                let destino = base_url + 'assets/images/productos/' + idPro + '/';
                for (let i = 0; i < res.length; i++) {
                    html += `<div class="col-md-3 mb-3">
                        <div class="position-relative">
                            <img class="img-thumbnail" src="${destino + res[i]}" style="width:100%; height:200px; object-fit:contain;">
                            <div class="d-grid mt-2">
                                <button class="btn btn-danger btnEliminarImagen" type="button" data-id="${idPro}" data-name="${idPro + '/' + res[i]}">
                                    <i class="fas fa-trash me-2"></i>Eliminar
                            </button>
                        </div>
                    </div>
                </div>`;
                }
                containerGaleria.innerHTML = html;
                eliminarImagen();
                modalGaleria.show();
            }
        };
    }

    function eliminarImagen() {
        let lista = document.querySelectorAll('.btnEliminarImagen');
        for (let i = 0; i < lista.length; i++) {
            lista[i].addEventListener('click', function() {
                let idPro = lista[i].getAttribute('data-id');
                let nombre = lista[i].getAttribute('data-name');
                eliminar(idPro, nombre);
            })
        }
    }

    function eliminar(idPro, nombre) {
        const url = base_url + "productos/eliminarImg";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(JSON.stringify({
            url: nombre
        }));
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                Swal.fire("Aviso?", res.msg, res.icono);
                if (res.icono == 'success') {
                    agregarImagenes(idPro);
                }
            }
        };
    }
});