// JS personalizado para la tienda principal
// Inicializar todos los carruseles de productos y manejar errores de imágenes

$(function() {
  // Inicializar todos los carruseles de productos
  $('#carouselProductos, #carouselProductosAleatorios, #carouselProductosMejorValorados, #carouselProductosNuevos, #carouselProductosOferta').each(function() {
    $(this).carousel({
      interval: 5000,
      ride: 'carousel',
      wrap: true
    });
  });

  // Manejar las imágenes que no cargan
  $('img').on('error', function() {
    $(this).attr('src', BASE_URL + 'assets/images/product-placeholder.jpg');
  });
});

// Forzar visibilidad del body aunque haya error de JS
window.addEventListener('DOMContentLoaded', function() {
  document.body.style.visibility = 'visible';
});
