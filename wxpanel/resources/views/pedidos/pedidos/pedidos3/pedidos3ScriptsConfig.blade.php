<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidos3 = {};
appCustom.pedidos3.INDEX = {'url':appCustom.REST_URL + 'pedidos3', 'verb':'GET'};
appCustom.pedidos3.CREATE = {'url':appCustom.REST_URL + 'pedidos3/create', 'verb':'GET'};
appCustom.pedidos3.STORE = {'url':appCustom.REST_URL + 'pedidos3', 'verb':'POST'};
appCustom.pedidos3.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos3/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos3.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos3/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidos3.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos3/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidos3.metodopago = {};
appCustom.pedidos3.metodopago.STORE = {'url':appCustom.REST_URL + 'pedido3Metodopago', 'verb':'POST'};
appCustom.pedidos3.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Metodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos3.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Metodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidos3.estadopago = {};
appCustom.pedidos3.estadopago.STORE = {'url':appCustom.REST_URL + 'pedido3Estadopago', 'verb':'POST'};
appCustom.pedidos3.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Estadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos3.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Estadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidos3.estadoenvio = {};
appCustom.pedidos3.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedido3Estadoenvio', 'verb':'POST'};
appCustom.pedidos3.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Estadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos3.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Estadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidos3.productos = {};
appCustom.pedidos3.productos.STORE = {'url':appCustom.REST_URL + 'pedido3Productos', 'verb':'POST'};
appCustom.pedidos3.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Productos/' + id + '/edit';
    },
    'verb': 'GET'
};
//notificaciones
appCustom.pedidos3.notificaciones = {};
appCustom.pedidos3.notificaciones.STORE = {'url':appCustom.REST_URL + 'pedido3Notificaciones', 'verb':'POST'};
appCustom.pedidos3.notificaciones.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido3Notificaciones/' + id + '/edit';
    },
    'verb': 'GET'
};
</script>
