<?php
/**
 * Obtiene la URL completa de una imagen
 * @param string $imagen Ruta de la imagen o URL
 * @return string URL completa de la imagen
 */
function getImageUrl($imagen) {
    if (empty($imagen)) {
        return BASE_URL . DEFAULT_PRODUCT_IMAGE;
    }
    
    if (filter_var($imagen, FILTER_VALIDATE_URL)) {
        return $imagen;
    }
    
    return BASE_URL . (strpos($imagen, 'assets/') === 0 ? $imagen : PRODUCTS_PATH . $imagen);
}