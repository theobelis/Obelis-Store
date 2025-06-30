// Función para cargar productos recomendados
async function cargarProductosRecomendados() {
    try {
        const response = await fetch(base_url + 'principal/productosRecomendados');
        const productos = await response.json();
        
        const contenedor = document.getElementById('productosRecomendados');
        let html = '';
        
        productos.forEach(producto => {            html += `
            <div class="col-md-3 col-sm-6">                <div class="producto-card">
                    <div class="img-container">
                        <img src="${base_url}assets/images/productos/${producto.imagen}" 
                            alt="${producto.nombre}"
                            onerror="this.src='${base_url}assets/images/product-placeholder.png'"
                            loading="lazy">
                    </div>
                    <div class="producto-info">
                        <h5>${producto.nombre}</h5>
                        <div class="producto-precio">
                            $${parseFloat(producto.precio).toFixed(2)}
                        </div>
                        <button class="btn btn-primary btnAddCarrito" data-id="${producto.id}" 
                                data-nombre="${producto.nombre}" data-precio="${producto.precio}" 
                                data-cantidad="1" type="button">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            </div>`;
        });
        
        contenedor.innerHTML = html;
    } catch (error) {
        console.error('Error al cargar productos recomendados:', error);
    }
}

// Cargar productos recomendados cuando se carga la página
document.addEventListener('DOMContentLoaded', cargarProductosRecomendados);
