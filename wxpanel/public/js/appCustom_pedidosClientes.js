//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.pedidosClientes = {};
appCustom.pedidosClientes.INDEX = {'url':appCustom.REST_URL + 'pedidosClientes', 'verb':'GET'};
appCustom.pedidosClientes.CREATE = {'url':appCustom.REST_URL + 'pedidosClientes/create', 'verb':'GET'};
appCustom.pedidosClientes.STORE = {'url':appCustom.REST_URL + 'pedidosClientes', 'verb':'POST'};
appCustom.pedidosClientes.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientes/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.pedidosClientes.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientes/' + id;
    },
    'verb': 'PUT'
};
appCustom.pedidosClientes.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientes/' + id;
    },
    'verb': 'DELETE'
};

//direcciones related

appCustom.pedidosClientes.direccionesRelated = {};

appCustom.pedidosClientes.direccionesRelated.mainView = {
    'url':function(id){
        return 'pedidosClientes/direccionesRelatedMain/' + id;
    }
};

appCustom.pedidosClientes.direccionesRelated.INDEX = {'url':appCustom.REST_URL + 'direccionesRelated', 'verb':'GET'};
appCustom.pedidosClientes.direccionesRelated.STORE = {'url':appCustom.REST_URL + 'direccionesRelated', 'verb':'POST'};
appCustom.pedidosClientes.direccionesRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'direccionesRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.pedidosClientes.direccionesRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'direccionesRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.pedidosClientes.direccionesRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'direccionesRelated/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.pedidosClientes.image = {};

appCustom.pedidosClientes.image.mainView = {
    'url':function(id){
        return 'pedidosClientes/imageMain/' + id;
    }
};

appCustom.pedidosClientes.image.INDEX = {'url':appCustom.REST_URL + 'pedidosClientesImage', 'verb':'GET'};
appCustom.pedidosClientes.image.STORE = {'url':appCustom.REST_URL + 'pedidosClientesImage', 'verb':'POST'};
appCustom.pedidosClientes.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientesImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.pedidosClientes.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientesImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.pedidosClientes.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'pedidosClientesImage/' + id;
    },
    'verb': 'DELETE'
};