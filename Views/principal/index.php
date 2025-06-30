<?php
// Este archivo está diseñado para ser la vista de la página de "Mi Cuenta" para clientes.
// Hemos eliminado la lógica que incluía 'pagina_editada.html' o mostraba el código fuente,
// ya que impedía que la interfaz de usuario de la cuenta se renderizara correctamente.

// Definir $foto antes de cualquier uso.
// Es crucial que $data['cliente'] esté disponible y contenga la información necesaria.
// Se usa htmlspecialchars para prevenir XSS en las salidas de datos.
$foto = empty($data['cliente']['foto'])
    ? BASE_URL . 'assets/principal/img/no_foto.png'
    : BASE_URL . 'assets/images/fotos_clientes/' . htmlspecialchars($data['cliente']['id']) . '/' . htmlspecialchars($data['cliente']['foto']);
    ?>
    <?php include_once 'Views/template/header-principal.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Cuenta - Clientes</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@material-dashboard/bootstrap@4.0.0/assets/css/material-dashboard.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/DataTables/datatables.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/cliente-area.css">
</head>
<body class="g-sidenav-show bg-gray-200">

<?php 
// Asegúrate de que $data['verificar'] y $data['verificar']['verify'] existan
if (isset($data['verificar']['verify']) && $data['verificar']['verify'] == 1) { 
?>
<div class="min-height-300 bg-primary position-absolute w-100"></div>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="#">
            <img src="<?php echo $foto; ?>" class="navbar-brand-img h-100 rounded-circle border" alt="Foto de perfil">
            <span class="ms-1 font-weight-bold"><?php echo htmlspecialchars($data['cliente']['nombre'] . ' ' . $data['cliente']['apellido']); ?></span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">home</i>
                    </div>
                    <span class="nav-link-text ms-1">Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <button class="nav-link active w-100" id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders" type="button">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">inventory_2</i>
                    </div>
                    <span class="nav-link-text ms-1">Mis Pedidos</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link w-100" id="v-pills-cart-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cart" type="button">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1">Carrito Actual</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link w-100" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1">Mi Perfil</span>
                </button>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Próximamente sección de ayuda.'); return false;">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">help</i>
                    </div>
                    <span class="nav-link-text ms-1">Ayuda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="alert('Próximamente soporte.'); return false;">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">support_agent</i>
                    </div>
                    <span class="nav-link-text ms-1">Soporte</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link" href="<?php echo BASE_URL . 'clientes/salir'; ?>">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">logout</i>
                    </div>
                    <span class="nav-link-text ms-1">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-orders" role="tabpanel">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">inventory_2</i>
                                </div>
                                <div class="text-end pt-1">
                                    <h4 class="mb-0">Historial de Pedidos</h4>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="tblPendientes">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Monto</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-cart" role="tabpanel">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">shopping_cart</i>
                                </div>
                                <div class="text-end pt-1">
                                    <h4 class="mb-0">Carrito de Compras</h4>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="tableListaProductos">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Producto</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-0" id="totalProducto"></h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">person</i>
                                </div>
                                <div class="text-end pt-1">
                                    <h4 class="mb-0">Modificar Datos Personales</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formPerfil" method="post" autocomplete="off" enctype="multipart/form-data" novalidate>
                                    <div class="text-center mb-4">
                                        <?php
                                        // Asegúrate de que $data['cliente'] esté disponible para evitar errores.
                                        $foto = empty($data['cliente']['foto']) ? 
                                            BASE_URL . 'assets/img/default-avatar.png' : 
                                            BASE_URL . 'assets/images/fotos_clientes/' . htmlspecialchars($data['cliente']['id']) . '/' . htmlspecialchars($data['cliente']['foto']);
                                        ?>
                                        <img id="previewFoto" class="img-thumbnail rounded-circle" 
                                             src="<?php echo $foto; ?>" 
                                             alt="Foto de perfil" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                                    pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                                    title="Solo se permiten letras y espacios"
                                                    value="<?php echo htmlspecialchars($data['cliente']['nombre']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Apellido</label>
                                                <input type="text" class="form-control" id="apellido" name="apellido" 
                                                    pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                                    title="Solo se permiten letras y espacios"
                                                    value="<?php echo htmlspecialchars($data['cliente']['apellido']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Correo electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" 
                                            value="<?php echo htmlspecialchars($data['cliente']['correo']); ?>" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Nueva contraseña</label>
                                        <input type="password" class="form-control" id="clave" name="clave" 
                                            placeholder="Dejar en blanco para no cambiar">
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Guardar cambios</button>
                                    </div>
                                </form>
                                <div id="msgPerfil" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php } else { ?>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute start-50 translate-middle-x">
                        <i class="material-icons opacity-10">warning</i>
                    </div>
                    <h3 class="mt-5 pt-3">VERIFICA TU CORREO ELECTRONICO</h3>
                    <p class="text-secondary">Por favor, verifica tu correo electrónico para acceder a tu cuenta.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/smooth-scrollbar@8.8.4/dist/smooth-scrollbar.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@material-dashboard/bootstrap@4.0.0/assets/js/material-dashboard.min.js"></script>

<script type="text/javascript" src="<?php echo BASE_URL . 'assets/DataTables/datatables.min.js'; ?>"></script>
<script src="<?php echo BASE_URL; ?>assets/js/es-ES.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo BASE_URL . 'assets/js/clientes.js'; ?>"></script>
<script src="<?php echo BASE_URL . 'assets/js/perfil.js'; ?>"></script>
<script src="<?php echo BASE_URL . 'assets/js/recomendados.js'; ?>"></script>

<script>
    // Inicialización de Material Dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar los input groups
        const inputs = document.querySelectorAll('.input-group-outline input');
        inputs.forEach(input => {
            if (input.value) {
                input.parentElement.classList.add('is-filled');
            }
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('is-focused');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('is-focused');
                if (input.value) {
                    input.parentElement.classList.add('is-filled');
                } else {
                    input.parentElement.classList.remove('is-filled');
                }
            });
        });

        // Inicializar sidenav (Asegúrate de que MaterialDashboard.Sidenav esté disponible)
        if (typeof MaterialDashboard !== 'undefined' && MaterialDashboard.Sidenav) {
            const sidenav = document.querySelector('#sidenav-main');
            if (sidenav) {
                new MaterialDashboard.Sidenav(sidenav); // No necesitas .init() si el constructor ya lo hace
            }
        } else {
            console.warn("MaterialDashboard o MaterialDashboard.Sidenav no están definidos. No se pudo inicializar el sidenav.");
        }


        // Inicializar tooltips (Asegúrate de que bootstrap.Tooltip esté disponible)
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        } else {
            console.warn("Bootstrap o bootstrap.Tooltip no están definidos. No se pudieron inicializar los tooltips.");
        }
    });
</script>

</body>
</html>