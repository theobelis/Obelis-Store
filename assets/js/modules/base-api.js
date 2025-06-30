// Módulo base para operaciones AJAX
const BaseApi = {
    // Token de autenticación
    token: null,

    // Configurar el token
    setToken(token) {
        this.token = token;
    },

    // Obtener headers con autenticación si existe token
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json'
        };
        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }
        return headers;
    },

    // Manejar errores de respuesta
    handleError(error, url) {
        console.error(`Error en petición a ${url}:`, error);
        
        if (error.status === 401) {
            // Redirigir al login si no está autenticado
            window.location.href = CONFIG.BASE_URL + 'login';
            return;
        }

        // Mostrar error al usuario con SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ha ocurrido un error inesperado'
        });

        throw error;
    },

    // Método base para realizar peticiones
    async request(url, options = {}) {
        const defaultOptions = {
            headers: this.getHeaders()
        };
        
        try {
            const response = await fetch(CONFIG.BASE_URL + url, { ...defaultOptions, ...options });
            const data = await response.json();
            
            if (!response.ok) {
                throw {
                    status: response.status,
                    message: data.msg || 'Error en la petición',
                    data
                };
            }
            
            return data;
        } catch (error) {
            this.handleError(error, url);
        }
    },

    get(url) {
        return this.request(url);
    },

    post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    delete(url) {
        return this.request(url, {
            method: 'DELETE'
        });
    }
};
