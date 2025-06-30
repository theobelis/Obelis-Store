<?php include_once 'Views/template/header-admin.php'; ?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#listaProducto" type="button" role="tab" aria-controls="listaProducto" aria-selected="true">Productos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#nuevoProducto" type="button" role="tab" aria-controls="nuevoProducto" aria-selected="false">Nuevo</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="listaProducto" role="tabpanel" aria-labelledby="home-tab">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" style="width: 100%;" id="tblProductos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Imagen</th>
                                <th>Destacado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- Aquí se llenará dinámicamente con JS/AJAX, pero la columna de destacado debe estar presente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nuevoProducto" role="tabpanel" aria-labelledby="profile-tab">
        <div class="card">
            <div class="card-body p-5">
                <form id="frmRegistro">
                    <div class="row">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="imagen_actual" name="imagen_actual">
                        <div class="col-md-5">
                            <label for="nombre">Título</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="nombre">Título</label>
                                <input id="nombre" class="form-control" type="text" name="nombre">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="precio">Precio</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="precio">Precio</label>
                                <input id="precio" class="form-control" type="text" name="precio">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad">Cantidad</label>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label" for="cantidad">Cantidad</label>
                                <input id="cantidad" class="form-control" type="number" name="cantidad">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="categoria">Categoria</label>
                            <div class="input-group input-group-outline my-3">
                                <select id="categoria" class="form-control" name="categoria">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['categorias'] as $categoria) { ?>
                                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="destacado">¿Destacado?</label>
                            <div class="input-group input-group-outline my-3">
                                <select id="destacado" class="form-control" name="destacado">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="descripcion">Descripción</label>
                            <div class="input-group input-group-outline my-3">
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label>Imágenes del producto</label>
                            <form action="<?php echo BASE_URL . 'productos/galeriaImagenes'; ?>" class="dropzone" id="dropzoneNuevoProducto">
                                <input type="hidden" id="idProductoNuevo" name="idProducto">
                            </form>
                            <small class="form-text text-muted">Puedes arrastrar varias imágenes o hacer clic para seleccionarlas.</small>
                        </div>
                        <div class="col-md-3">
                            <label for="colores">Colores disponibles para este producto</label>
                            <div class="input-group input-group-outline my-3 align-items-center gap-2">
                                <input type="color" id="colorPicker" class="form-control form-control-color" value="#00e1ff" title="Elige un color">
                                <button type="button" class="btn btn-info" id="btnAgregarColor">Agregar</button>
                                <input type="hidden" id="colores" name="colores">
                            </div>
                            <div id="listaColores" class="d-flex gap-2 flex-wrap mb-2"></div>
                            <small class="form-text text-muted">Haz clic en un color para quitarlo. Puedes agregar cualquier color.</small>
                        </div>
                        <div class="col-md-3">
                            <label for="tallas">Tallas disponibles para este producto</label>
                            <div class="input-group input-group-outline my-3 align-items-center gap-2">
                                <input type="text" id="inputTalla" class="form-control" placeholder="Ej: S, M, L, 38, 40, etc">
                                <button type="button" class="btn btn-info" id="btnAgregarTalla">Agregar</button>
                                <input type="hidden" id="tallas" name="tallas">
                            </div>
                            <div id="listaTallas" class="d-flex gap-2 flex-wrap mb-2"></div>
                            <small class="form-text text-muted">Haz clic en una talla para quitarla. Puedes agregar cualquier talla personalizada.</small>
                        </div>
                        <div class="col-md-3">
                            <label for="calificacion">Calificación inicial</label>
                            <div class="input-group input-group-outline my-3">
                                <input id="calificacion" class="form-control" type="number" name="calificacion" min="0" max="5" step="0.1" placeholder="Ej: 4.5">
                                <small class="form-text text-muted">Opcional, 0-5</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalGaleria" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Imagenes del Producto</h5>
                <button class="btn-close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo BASE_URL . 'productos/galeriaImagenes'; ?>" class="dropzone">
                    <input type="hidden" id="idProducto" name="idProducto">
                </form>
                <div class="text-end mt-3">
                    <button class="btn btn-primary" type="button" id="btnProcesar">Subir Imagenes</button>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row justify-content-between" id="containerGaleria">
                            <!-- Galería de imágenes con opción de ordenar -->
                        </div>
                        <div class="text-end mt-3">
                            <button class="btn btn-secondary" type="button" id="btnOrdenarImagenes">
                                <i class="fas fa-sort"></i> Ordenar Imágenes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'Views/template/footer-admin.php'; ?>

<script src="<?php echo BASE_URL . 'assets/js/modulos/productos.js'; ?>"></script>
<!-- CDN de SortableJS para drag & drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.Dropzone) {
        Dropzone.autoDiscover = false;
        var dropzoneNuevo = new Dropzone('#dropzoneNuevoProducto', {
            url: '<?php echo BASE_URL . 'productos/galeriaImagenes'; ?>',
            paramName: 'file',
            maxFilesize: 5, // MB
            maxFiles: 10,
            acceptedFiles: 'image/jpeg,image/png',
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 1,
            dictDefaultMessage: 'Arrastra y suelta imágenes aquí o haz clic para seleccionar',
            init: function() {
                this.on('addedfile', function(file) {
                    if (file.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            file.previewElement.querySelector('img').src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
                this.on('success', function(file, response) {
                    if (response && response.error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.error });
                        this.removeFile(file);
                    }
                });
                this.on('error', function(file, errorMessage) {
                    Swal.fire({ icon: 'error', title: 'Error', text: errorMessage });
                });
            }
        });
        document.querySelector('#frmRegistro').addEventListener('submit', function(e) {
            e.preventDefault();
            let data = new FormData(this);
            const url = '<?php echo BASE_URL; ?>productos/registrar';
            const http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.send(data);
            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res.icono == 'success') {
                        if (res.idProducto && dropzoneNuevo.getQueuedFiles().length > 0) {
                            document.querySelector('#idProductoNuevo').value = res.idProducto;
                            dropzoneNuevo.options.url = '<?php echo BASE_URL . 'productos/galeriaImagenes'; ?>';
                            dropzoneNuevo.options.autoProcessQueue = true;
                            dropzoneNuevo.processQueue();
                        } else {
                            document.querySelector('#frmRegistro').reset();
                            tblProductos.ajax.reload();
                        }
                    }
                    Swal.fire('Aviso?', res.msg.toUpperCase(), res.icono);
                }
            };
        });
        dropzoneNuevo.on("queuecomplete", function() {
            document.querySelector('#frmRegistro').reset();
            tblProductos.ajax.reload();
            dropzoneNuevo.removeAllFiles();
        });
    }
    // Habilitar ordenamiento drag & drop en la galería
    let sortable;
    document.getElementById('btnOrdenarImagenes').addEventListener('click', function() {
        if (!window.Sortable) {
            Swal.fire('Error', 'Falta la librería SortableJS para ordenar imágenes', 'error');
            return;
        }
        if (!sortable) {
            sortable = new Sortable(document.getElementById('containerGaleria'), {
                animation: 150,
                onEnd: function (evt) {
                    // Guardar nuevo orden en el backend
                    let orden = [];
                    document.querySelectorAll('#containerGaleria .img-thumbnail').forEach(img => {
                        orden.push(img.getAttribute('data-nombre'));
                    });
                    fetch(base_url + 'productos/ordenarGaleria', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ idProducto: document.querySelector('#idProducto').value, orden: orden })
                    })
                    .then(r => r.json())
                    .then(res => {
                        Swal.fire('Orden actualizado', res.msg, res.icono);
                    });
                }
            });
            Swal.fire('Arrastra las imágenes para reordenar y suelta. El orden se guarda automáticamente.');
        }
    });

    // Lógica para agregar/quitar colores
    const listaColores = document.getElementById('listaColores');
    const inputColores = document.getElementById('colores');
    document.getElementById('btnAgregarColor').addEventListener('click', function() {
        const colorPicker = document.getElementById('colorPicker');
        const colorValue = colorPicker.value;
        if (!colorValue) return;
        const colorDiv = document.createElement('div');
        colorDiv.className = 'color-swatch';
        colorDiv.style.backgroundColor = colorValue;
        colorDiv.style.width = '30px';
        colorDiv.style.height = '30px';
        colorDiv.style.borderRadius = '50%';
        colorDiv.style.display = 'inline-block';
        colorDiv.style.cursor = 'pointer';
        colorDiv.setAttribute('data-color', colorValue);
        colorDiv.title = 'Haz clic para quitar';
        colorDiv.addEventListener('click', function() {
            this.remove();
            actualizarColores();
        });
        listaColores.appendChild(colorDiv);
        actualizarColores();
    });
    function actualizarColores() {
        const colores = [];
        document.querySelectorAll('#listaColores .color-swatch').forEach(div => {
            colores.push(div.getAttribute('data-color'));
        });
        inputColores.value = colores.join(',');
    }

    // Lógica para agregar/quitar tallas
    const listaTallas = document.getElementById('listaTallas');
    const inputTallas = document.getElementById('tallas');
    document.getElementById('btnAgregarTalla').addEventListener('click', function() {
        const inputTalla = document.getElementById('inputTalla');
        const tallaValue = inputTalla.value.trim();
        if (!tallaValue) return;
        const tallaDiv = document.createElement('div');
        tallaDiv.className = 'talla-badge';
        tallaDiv.style.backgroundColor = '#e9ecef';
        tallaDiv.style.color = '#495057';
        tallaDiv.style.padding = '5px 10px';
        tallaDiv.style.borderRadius = '20px';
        tallaDiv.style.display = 'inline-block';
        tallaDiv.style.cursor = 'pointer';
        tallaDiv.setAttribute('data-talla', tallaValue);
        tallaDiv.textContent = tallaValue;
        tallaDiv.addEventListener('click', function() {
            this.remove();
            actualizarTallas();
        });
        listaTallas.appendChild(tallaDiv);
        inputTalla.value = '';
        actualizarTallas();
    });
    function actualizarTallas() {
        const tallas = [];
        document.querySelectorAll('#listaTallas .talla-badge').forEach(div => {
            tallas.push(div.getAttribute('data-talla'));
        });
        inputTallas.value = tallas.join(',');
    }
});
</script>

</body>

</html>