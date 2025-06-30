// Referencias a elementos del DOM
const tableLista = document.querySelector("#tableListaProductos tbody");
const tblPendientes = document.querySelector('#tblPendientes');
const estadoEnviado = document.querySelector('#estadoEnviado');
const estadoProceso = document.querySelector('#estadoProceso');
const estadoCompletado = document.querySelector('#estadoCompletado');

// Estado local
let productosjson = [];
document.addEventListener("DOMContentLoaded", function() {
    if (tableLista) {
        getListaProductos();
    }
    //cargar datos pendientes con DataTables
    $('#tblPendientes').DataTable({
        ajax: {
            url: base_url + 'clientes/listarPendientes',
            dataSrc: ''
        },
        columns: [
            { data: 'id_transaccion' },
            { data: 'monto' },
            { data: 'fecha' },
            { data: 'accion' }
        ],
        language,
        dom,
        buttons
    });
});

async function getListaProductos() {
    try {
        let html = '';
        const url = base_url + 'principal/listaProductos';
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(listaCarrito),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const res = await response.json();
        
        if (res.totalPaypal > 0) {
            res.productos.forEach(producto => {
                html += `<tr>
                    <td>
                        <img class="img-thumbnail rounded-circle" src="${producto.imagen}" alt="" width="100">
                    </td>
                    <td>${producto.nombre}</td>
                    <td><span class="badge bg-warning">${res.moneda + ' ' + producto.precio}</span></td>
                    <td><span class="badge bg-primary">${producto.cantidad}</span></td>
                    <td>${producto.subTotal}</td>
                </tr>`;
                //agregar producto para paypal
                productosjson.push({
                    "name": producto.nombre,
                    "unit_amount": {
                        "currency_code": res.moneda,
                        "value": producto.precio
                    },
                    "quantity": producto.cantidad
                });
            });
            
            tableLista.innerHTML = html;
            document.querySelector('#totalProducto').textContent = 'TOTAL A PAGAR: ' + res.moneda + ' ' + res.total;
            botonPaypal(res.totalPaypal, res.moneda);
        } else {
            tableLista.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">CARRITO VACIO</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error al cargar productos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los productos del carrito'
        });
    }
}

function botonPaypal(total, moneda) {
    paypal.Buttons({
        createOrder: (data, actions) => {
            return actions.order.create({
                "purchase_units": [{
                    "amount": {
                        "currency_code": moneda,
                        "value": total,
                        "breakdown": {
                            "item_total": {
                                "currency_code": moneda,
                                "value": total
                            }
                        }
                    },
                    "items": productosjson
                }]
            });
        },
        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                registrarPedido(orderData)
            });
        }
    }).render('#paypal-button-container');
}

async function registrarPedido(datos) {
    try {
        const url = base_url + 'clientes/registrarPedido';
        const response = await fetch(url, {
            method: 'POST',
            body: JSON.stringify({
                pedidos: datos,
                productos: listaCarrito
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const res = await response.json();
        await Swal.fire("Aviso", res.msg, res.icono);
        
        if (res.icono === 'success') {
            localStorage.removeItem('listaCarrito');
            window.location.reload();
        }
    } catch (error) {
        console.error('Error al registrar pedido:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo registrar el pedido'
        });
    }
}

async function verPedido(idPedido) {
    try {
        // Limpiar estados previos
        estadoEnviado.classList.remove('bg-info');
        estadoProceso.classList.remove('bg-info');
        estadoCompletado.classList.remove('bg-info');

        const mPedido = new bootstrap.Modal(document.getElementById('modalPedido'));
        const url = base_url + 'clientes/verPedido/' + idPedido;
        
        const response = await fetch(url);
        const res = await response.json();
        
        // Actualizar estado
        if (res.pedido.proceso == 1) {
            estadoEnviado.classList.add('bg-info');
        } else if (res.pedido.proceso == 2) {
            estadoProceso.classList.add('bg-info');
        } else {
            estadoCompletado.classList.add('bg-info');
        }

        // Generar HTML de productos
        const html = res.productos.map(row => {
            const colorHtml = row.color ? `<div><span class='badge bg-secondary'>Color: ${row.color}</span></div>` : '';
            const tallaHtml = row.talla ? `<div><span class='badge bg-info'>Talla: ${row.talla}</span></div>` : '';
            const subTotal = (parseFloat(row.precio) * parseInt(row.cantidad)).toFixed(2);
            
            return `<tr>
                <td>${row.producto}${colorHtml}${tallaHtml}</td>
                <td><span class="badge bg-warning">${res.moneda + ' ' + row.precio}</span></td>
                <td><span class="badge bg-primary">${row.cantidad}</span></td>
                <td>${subTotal}</td>
            </tr>`;
        }).join('');

        document.querySelector('#tablePedidos tbody').innerHTML = html;
        mPedido.show();
    } catch (error) {
        console.error('Error al ver pedido:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar el detalle del pedido'
        });
    }
}