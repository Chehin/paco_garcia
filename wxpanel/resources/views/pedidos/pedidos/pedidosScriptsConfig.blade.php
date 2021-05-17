<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidos = {};
appCustom.pedidos.INDEX = {'url':appCustom.REST_URL + 'pedidos', 'verb':'GET'};
appCustom.pedidos.CREATE = {'url':appCustom.REST_URL + 'pedidos/create', 'verb':'GET'};
appCustom.pedidos.STORE = {'url':appCustom.REST_URL + 'pedidos', 'verb':'POST'};
appCustom.pedidos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidos.metodopago = {};
appCustom.pedidos.metodopago.STORE = {'url':appCustom.REST_URL + 'pedidoMetodopago', 'verb':'POST'};
appCustom.pedidos.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidos.estadopago = {};
appCustom.pedidos.estadopago.STORE = {'url':appCustom.REST_URL + 'pedidoEstadopago', 'verb':'POST'};
appCustom.pedidos.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidos.estadoenvio = {};
appCustom.pedidos.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedidoEstadoenvio', 'verb':'POST'};
appCustom.pedidos.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidos.productos = {};
appCustom.pedidos.productos.STORE = {'url':appCustom.REST_URL + 'pedidoProductos', 'verb':'POST'};
appCustom.pedidos.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoProductos/' + id + '/edit';
    },
    'verb': 'GET'
};
//notificaciones
appCustom.pedidos.notificaciones = {};
appCustom.pedidos.notificaciones.STORE = {'url':appCustom.REST_URL + 'pedidoNotificaciones', 'verb':'POST'};
appCustom.pedidos.notificaciones.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoNotificaciones/' + id + '/edit';
    },
    'verb': 'GET'
};

</script>
