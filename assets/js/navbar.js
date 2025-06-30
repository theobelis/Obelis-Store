document.addEventListener('DOMContentLoaded', function() {
    // Animación de la barra de búsqueda
    const searchToggle = document.querySelector('.search-toggle');
    const searchInput = document.querySelector('.search-input');

    if (searchToggle && searchInput) {
        searchToggle.addEventListener('click', function() {
            searchInput.classList.toggle('active');
            if (searchInput.classList.contains('active')) {
                searchInput.focus();
            }
        });

        // Cerrar búsqueda al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-animated') && searchInput.classList.contains('active')) {
                searchInput.classList.remove('active');
            }
        });
    }

    // Cerrar menú al hacer clic en un enlace (en móvil)
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) { // Solo en móvil
                navbarCollapse.classList.remove('show');
            }
        });
    });
});
