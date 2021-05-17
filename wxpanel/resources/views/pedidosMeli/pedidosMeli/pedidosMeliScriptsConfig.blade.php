<script>
	
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidosMeli = {};
appCustom.pedidosMeli.INDEX = {'url':appCustom.REST_URL + 'pedidosMeli', 'verb':'GET'};
appCustom.pedidosMeli.CREATE = {'url':appCustom.REST_URL + 'pedidosMeli/create', 'verb':'GET'};
appCustom.pedidosMeli.STORE = {'url':appCustom.REST_URL + 'pedidosMeli', 'verb':'POST'};
appCustom.pedidosMeli.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosMeli/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosMeli.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosMeli/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidosMeli.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosMeli/' + id;
    },
    'verb': 'DELETE'
};


//metodopago
appCustom.pedidosMeli.metodopago = {};
appCustom.pedidosMeli.metodopago.STORE = {'url':appCustom.REST_URL + 'pedidoMetodopago', 'verb':'POST'};
appCustom.pedidosMeli.metodopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosMeli.metodopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoMetodopago/' + id;
    },
    'verb': 'PUT'
};

//estadopago
appCustom.pedidosMeli.estadopago = {};
appCustom.pedidosMeli.estadopago.STORE = {'url':appCustom.REST_URL + 'pedidoEstadopago', 'verb':'POST'};
appCustom.pedidosMeli.estadopago.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosMeli.estadopago.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadopago/' + id;
    },
    'verb': 'PUT'
};
//estadoenvio
appCustom.pedidosMeli.estadoenvio = {};
appCustom.pedidosMeli.estadoenvio.STORE = {'url':appCustom.REST_URL + 'pedidoEstadoenvio', 'verb':'POST'};
appCustom.pedidosMeli.estadoenvio.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosMeli.estadoenvio.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoEstadoenvio/' + id;
    },
    'verb': 'PUT'
};
//productos
appCustom.pedidosMeli.productos = {};
appCustom.pedidosMeli.productos.STORE = {'url':appCustom.REST_URL + 'pedidoProductos', 'verb':'POST'};
appCustom.pedidosMeli.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidoProductos/' + id + '/edit';
    },
    'verb': 'GET'
};

</script>
