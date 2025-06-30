<?php include_once 'Views/template/header-principal.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Mi Perfil</h3>
                </div>
                <div class="card-body">
                    <form id="formPerfil" autocomplete="off">
                        <div class="text-center mb-4">
                            <img class="img-thumbnail rounded-circle" src="<?php echo BASE_URL . 'assets/img/logo.png'; ?>" alt="Foto de perfil" width="100">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($data['cliente']['nombre'] ?? $_SESSION['nombreCliente'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($data['cliente']['apellido'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($data['cliente']['correo'] ?? $_SESSION['correoCliente'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                            <a class="btn btn-danger" href="<?php echo BASE_URL . 'clientes/salir'; ?>"><i class="fas fa-times-circle"></i> Cerrar Sesión</a>
                        </div>
                    </form>
                    <div id="msgPerfil" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('formPerfil').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const datos = {
        nombre: form.nombre.value,
        apellido: form.apellido.value,
        correo: form.correo.value,
        clave: form.clave.value
    };
    fetch('<?php echo BASE_URL; ?>clientes/actualizarPerfil', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(res => {
        const msg = document.getElementById('msgPerfil');
        if(res.icono === 'success') {
            msg.innerHTML = `<div class='alert alert-success'>${res.msg}</div>`;
            setTimeout(()=>window.location.reload(), 1500);
        } else {
            msg.innerHTML = `<div class='alert alert-danger'>${res.msg}</div>`;
        }
    })
    .catch(()=>{
        document.getElementById('msgPerfil').innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
    });
});
</script>
<?php include_once 'Views/template/footer-principal.php'; ?>