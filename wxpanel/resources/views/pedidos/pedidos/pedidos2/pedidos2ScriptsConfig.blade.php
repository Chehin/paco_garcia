<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidos2 = {};
appCustom.pedidos2.INDEX = {'url':appCustom.REST_URL + 'pedidos2', 'verb':'GET'};
appCustom.pedidos2.CREATE = {'url':appCustom.REST_URL + 'pedidos2/create', 'verb':'GET'};
appCustom.pedidos2.STORE = {'url':appCustom.REST_URL + 'pedidos2', 'verb':'POST'};
appCustom.pedidos2.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos2/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos2.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos2/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidos2.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos2/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidos2.metodopago = {};
appCustom.pedidos2.metodopago.STORE = {'url':appCustom.REST_URL + 'pedido2Metodopago', 'verb':'POST'};
appCustom.pedidos2.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Metodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos2.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Metodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidos2.estadopago = {};
appCustom.pedidos2.estadopago.STORE = {'url':appCustom.REST_URL + 'pedido2Estadopago', 'verb':'POST'};
appCustom.pedidos2.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Estadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos2.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Estadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidos2.estadoenvio = {};
appCustom.pedidos2.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedido2Estadoenvio', 'verb':'POST'};
appCustom.pedidos2.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Estadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos2.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Estadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidos2.productos = {};
appCustom.pedidos2.productos.STORE = {'url':appCustom.REST_URL + 'pedido2Productos', 'verb':'POST'};
appCustom.pedidos2.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido2Productos/' + id + '/edit';
    },
    'verb': 'GET'
};
//notificaciones
appCustom.pedidos2.notificaciones = {};
appCustom.pedidos2.notificaciones.STORE = {'url':appCustom.REST_URL + 'pedido2Notificaciones', 'verb':'POST'};
appCustom.pedidos2.notificaciones.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'pedido2Notificaciones/' + id + '/edit';
    },
    'verb': 'GET'
};

</script>
