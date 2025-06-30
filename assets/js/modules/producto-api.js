// API para operaciones con productos y carrito
const ProductoApi = {
    listar: () => BaseApi.get('principal/listaProductos'),
    buscar: (termino) => BaseApi.get(`principal/buscar/${termino}`),
    obtenerDetalles: (id) => BaseApi.get(`principal/producto/${id}`),
    obtenerDestacados: () => BaseApi.get('principal/productos/destacados'),
    getCarrito: () => {
        const carrito = localStorage.getItem('listaCarrito');
        return carrito ? JSON.parse(carrito) : [];
    },
    actualizarCarrito: (productos) => {
        localStorage.setItem('listaCarrito', JSON.stringify(productos));
        document.dispatchEvent(new CustomEvent('carritoActualizado'));
    },
    agregarAlCarrito: (producto) => {
        const carrito = ProductoApi.getCarrito();
        const existe = carrito.find(item => item.id == producto.id);
        
        if (existe) {
            existe.cantidad++;
        } else {
            carrito.push({...producto, cantidad: 1});
        }
        
        ProductoApi.actualizarCarrito(carrito);
        
        Swal.fire({
            title: 'Éxito',
            text: 'Producto agregado al carrito',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }
};
