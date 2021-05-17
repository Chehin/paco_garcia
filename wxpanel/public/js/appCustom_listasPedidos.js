//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.listasPedidos = {};
appCustom.listasPedidos.INDEX = {'url':appCustom.REST_URL + 'listasPedidos', 'verb':'GET'};
appCustom.listasPedidos.CREATE = {'url':appCustom.REST_URL + 'listasPedidos/create', 'verb':'GET'};
appCustom.listasPedidos.STORE = {'url':appCustom.REST_URL + 'listasPedidos', 'verb':'POST'};
appCustom.listasPedidos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.listasPedidos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidos/' + id;
    },
    'verb': 'PUT'
};
appCustom.listasPedidos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidos/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.listasPedidos.metodopago = {};
appCustom.listasPedidos.metodopago.STORE = {'url':appCustom.REST_URL + 'listasPedidoMetodopago', 'verb':'POST'};
appCustom.listasPedidos.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidoMetodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.listasPedidos.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidoMetodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.listasPedidos.estadopago = {};
appCustom.listasPedidos.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidoEstadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
//estadoenvio
appCustom.listasPedidos.estadoenvio = {};
appCustom.listasPedidos.estadoenvio.STORE = {'url':appCustom.REST_URL + 'listasPedidoEstadoenvio', 'verb':'POST'};
appCustom.listasPedidos.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidoEstadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
//productos
appCustom.listasPedidos.productos = {};
appCustom.listasPedidos.productos.STORE = {'url':appCustom.REST_URL + 'listasPedidoProductos', 'verb':'POST'};
appCustom.listasPedidos.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasPedidoProductos/' + id + '/edit';
    },
    'verb': 'GET'
};

