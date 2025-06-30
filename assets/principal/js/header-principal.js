$(document).ready(function() {
    const $searchToggle = $('.search-toggle');
    const $searchInput = $('#search');
    let isSearchVisible = false;

    function showSearch() {
        $searchInput
            .css('display', 'block')
            .css('width', '180px')
            .css('opacity', '1')
            .focus();
        isSearchVisible = true;
    }

    function hideSearch() {
        $searchInput
            .css('opacity', '0')
            .css('width', '0');
        setTimeout(() => {
            $searchInput.css('display', 'none');
        }, 700);
        isSearchVisible = false;
    }

    $searchToggle.on('click', function(e) {
        e.stopPropagation();
        if (!isSearchVisible) {
            showSearch();
        } else {
            hideSearch();
        }
    });

    $searchInput.on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', function(e) {
        if (isSearchVisible && !$(e.target).closest('.search-animated').length) {
            hideSearch();
        }
    });

    $('.search-animated').on('submit', function(e) {
        e.preventDefault();
        const searchTerm = $searchInput.val().trim();
        if (searchTerm) {
            // Aquí puedes implementar la lógica de búsqueda
            console.log('Término de búsqueda:', searchTerm);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchInput = document.querySelector('#search');
    let isSearchVisible = false;

    // Función para mostrar el input de búsqueda
    function showSearch() {
        searchInput.style.display = 'block';
        setTimeout(() => {
            searchInput.style.opacity = '1';
            searchInput.style.width = '100%';
            searchInput.focus();
        }, 50);
        isSearchVisible = true;
    }

    // Función para ocultar el input de búsqueda
    function hideSearch() {
        searchInput.style.opacity = '0';
        searchInput.style.width = '0';
        setTimeout(() => {
            searchInput.style.display = 'none';
        }, 700); // Este tiempo debe coincidir con la duración de la transición CSS
        isSearchVisible = false;
    }

    // Evento click para el botón de búsqueda
    searchToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (!isSearchVisible) {
            showSearch();
        } else {
            hideSearch();
        }
    });

    // Evento click en el input de búsqueda para evitar que se cierre
    searchInput.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Evento click en el documento para cerrar la búsqueda
    document.addEventListener('click', function() {
        if (isSearchVisible) {
            hideSearch();
        }
    });

    // Evento para el formulario de búsqueda
    const searchForm = document.querySelector('.search-animated');
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        // Aquí puedes agregar la lógica de búsqueda
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            // Implementar la lógica de búsqueda aquí
            console.log('Búsqueda:', searchTerm);
        }
    });
});
