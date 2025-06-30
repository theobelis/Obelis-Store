document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('#productosDestacadosCarousel');
    if (!carousel) return;

    // Configurar el carousel
    const bsCarousel = new bootstrap.Carousel(carousel, {
        interval: 5000,
        wrap: true,
        touch: true
    });

    // Manejar la carga de imágenes
    function handleImages(container) {
        const images = container.querySelectorAll('.img-zoom');
        images.forEach(img => {
            const container = img.closest('.img-zoom-container');
            container.classList.add('loading');

            // Crear una nueva imagen para precargar
            const tempImage = new Image();
            tempImage.onload = function() {
                container.classList.remove('loading');
                img.src = this.src;
                img.style.opacity = '1';
            };
            tempImage.onerror = function() {
                container.classList.remove('loading');
                img.src = `${BASE_URL}${DEFAULT_PRODUCT_IMAGE}`;
                img.style.opacity = '1';
            };
            tempImage.src = img.dataset.src || img.src;
        });
    }

    // Manejar transiciones
    carousel.addEventListener('slide.bs.carousel', function(e) {
        const nextSlide = e.relatedTarget;
        handleImages(nextSlide);
    });

    // Pausar cuando no es visible
    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    bsCarousel.cycle();
                } else {
                    bsCarousel.pause();
                }
            });
        },
        { threshold: 0.5 }
    );

    observer.observe(carousel);

    // Cargar imágenes iniciales
    handleImages(carousel);
});
