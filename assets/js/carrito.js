// Carrito de compras funcional para Obelis Store
const btnCarrito = document.querySelector("#btnCantidadCarrito");
const verCarrito = document.querySelector('#verCarrito');
const tableListaCarrito = document.querySelector('#tableListaCarrito tbody');

// Inicializar listaCarrito desde localStorage
if (typeof window.listaCarrito === 'undefined') {
    window.listaCarrito = [];
    if (localStorage.getItem("listaCarrito") != null) {
        window.listaCarrito = JSON.parse(localStorage.getItem("listaCarrito"));
    }
}
let listaCarrito = window.listaCarrito;

document.addEventListener("DOMContentLoaded", function () {
    // Configurar los botones de agregar al carrito
    document.querySelectorAll('.btnAddCarrito').forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const idProducto = this.getAttribute("data-id");
            agregarCarrito(idProducto, 1);
        });
    });

    // Mostrar cantidad inicial
    cantidadCarrito();

    // Configurar botón ver carrito
    if (verCarrito) {
        verCarrito.addEventListener('click', function () {
            getListaCarrito();
        });
    }
});

function agregarCarrito(idProducto, cantidad, color = null, talla = null) {
    if (localStorage.getItem("listaCarrito") == null) {
        listaCarrito = [];
    } else {
        let listaExiste = JSON.parse(localStorage.getItem("listaCarrito"));
        for (let i = 0; i < listaExiste.length; i++) {
            if (listaExiste[i]["idProducto"] == idProducto && 
                listaExiste[i]["color"] == color && 
                listaExiste[i]["talla"] == talla) {
                alertaPersonalizada("EL PRODUCTO YA ESTÁ AGREGADO", "warning");
                return;
            }
        }
        listaCarrito = listaExiste;
    }
    
    listaCarrito.push({
        idProducto: idProducto,
        cantidad: cantidad,
        color: color,
        talla: talla
    });
    
    localStorage.setItem("listaCarrito", JSON.stringify(listaCarrito));
    alertaPersonalizada("PRODUCTO AGREGADO AL CARRITO", "success");
    cantidadCarrito();
}

function cantidadCarrito() {
    if (!btnCarrito) return;

    let total = 0;
    if (localStorage.getItem("listaCarrito") != null) {
        let listaExiste = JSON.parse(localStorage.getItem("listaCarrito"));
        for (let i = 0; i < listaExiste.length; i++) {
            total += listaExiste[i]["cantidad"];
        }
    }
    btnCarrito.textContent = total;
}

function getListaCarrito() {
    if (!tableListaCarrito) return;

    if (localStorage.getItem("listaCarrito") != null) {
        const url = base_url + "principal/listaProductos";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(JSON.stringify(listaCarrito));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                let html = '';
                res.productos.forEach(producto => {
                    let colorBadge = producto.color ? 
                        `<span class="badge bg-secondary ms-2">Color: ${producto.color}</span>` : '';
                    let tallaBadge = producto.talla ? 
                        `<span class="badge bg-info ms-2">Talla: ${producto.talla}</span>` : '';
                    
                    html += `<tr>
                        <td>
                            <img class="img-thumbnail rounded-circle" src="${producto.imagen}" alt="" width="100">
                        </td>
                        <td>
                            ${producto.nombre}
                            ${colorBadge}
                            ${tallaBadge}
                        </td>
                        <td>
                            <span class="badge bg-warning">${res.moneda + ' ' + producto.precio}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">${producto.cantidad}</span>
                        </td>
                        <td>${producto.subTotal}</td>
                        <td>
                            <button class="btn btn-danger" type="button" onclick="eliminarListaCarrito(${producto.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <a href="${base_url}productos/detalle/${producto.id}" 
                               class="btn btn-info" 
                               target="_blank"
                               rel="noopener">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>`;
                });
                tableListaCarrito.innerHTML = html;
                document.querySelector("#totalProducto").textContent = 'TOTAL A PAGAR: ' + res.moneda + ' ' + res.total;
            }
        };
    } else {
        tableListaCarrito.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">CARRITO VACÍO</td>
            </tr>`;
    }
}

function eliminarListaCarrito(idProducto) {
    let lista = JSON.parse(localStorage.getItem("listaCarrito"));
    lista = lista.filter(item => item.idProducto != idProducto);
    localStorage.setItem("listaCarrito", JSON.stringify(lista));
    getListaCarrito();
    cantidadCarrito();
}

function alertaPersonalizada(msg, icono) {
    if (window.Swal) {
        Swal.fire({
            title: "Aviso",
            text: msg,
            icon: icono,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(msg);
    }
}