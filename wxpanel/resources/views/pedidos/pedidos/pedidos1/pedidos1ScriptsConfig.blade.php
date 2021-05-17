<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidos1 = {};
appCustom.pedidos1.INDEX = {'url':appCustom.REST_URL + 'pedidos1', 'verb':'GET'};
appCustom.pedidos1.CREATE = {'url':appCustom.REST_URL + 'pedidos1/create', 'verb':'GET'};
appCustom.pedidos1.STORE = {'url':appCustom.REST_URL + 'pedidos1', 'verb':'POST'};
appCustom.pedidos1.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos1/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos1.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos1/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidos1.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidos1/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidos1.metodopago = {};
appCustom.pedidos1.metodopago.STORE = {'url':appCustom.REST_URL + 'pedido1Metodopago', 'verb':'POST'};
appCustom.pedidos1.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Metodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos1.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Metodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidos1.estadopago = {};
appCustom.pedidos1.estadopago.STORE = {'url':appCustom.REST_URL + 'pedido1Estadopago', 'verb':'POST'};
appCustom.pedidos1.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Estadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos1.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Estadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidos1.estadoenvio = {};
appCustom.pedidos1.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedido1Estadoenvio', 'verb':'POST'};
appCustom.pedidos1.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Estadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidos1.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Estadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidos1.productos = {};
appCustom.pedidos1.productos.STORE = {'url':appCustom.REST_URL + 'pedido1Productos', 'verb':'POST'};
appCustom.pedidos1.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Productos/' + id + '/edit';
    },
    'verb': 'GET'
};
//notificaciones
appCustom.pedidos1.notificaciones = {};
appCustom.pedidos1.notificaciones.STORE = {'url':appCustom.REST_URL + 'pedido1Notificaciones', 'verb':'POST'};
appCustom.pedidos1.notificaciones.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedido1Notificaciones/' + id + '/edit';
    },
    'verb': 'GET'
};

</script>
