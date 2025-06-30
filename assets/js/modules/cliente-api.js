// API para operaciones de clientes
const ClienteApi = {
    login: (credenciales) => BaseApi.post('clientes/loginDirecto', credenciales),
    registro: (datos) => BaseApi.post('clientes/registroDirecto', datos),
    verificar: (token) => BaseApi.get(`clientes/verificar/${token}`),
    getPerfil: () => BaseApi.get('clientes/perfil'),
    actualizarPerfil: (datos) => BaseApi.put('clientes/actualizarPerfil', datos),
    getPedidos: () => BaseApi.get('clientes/listarPendientes'),
    verPedido: (id) => BaseApi.get(`clientes/verPedido/${id}`)
};
