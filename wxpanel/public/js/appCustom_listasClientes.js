//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.listasClientes = {};
appCustom.listasClientes.INDEX = {'url':appCustom.REST_URL + 'listasClientes', 'verb':'GET'};
appCustom.listasClientes.CREATE = {'url':appCustom.REST_URL + 'listasClientes/create', 'verb':'GET'};
appCustom.listasClientes.STORE = {'url':appCustom.REST_URL + 'listasClientes', 'verb':'POST'};
appCustom.listasClientes.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasClientes/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.listasClientes.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasClientes/' + id;
    },
    'verb': 'PUT'
};
appCustom.listasClientes.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasClientes/' + id;
    },
    'verb': 'DELETE'
};

//direcciones related

appCustom.listasClientes.direccionesRelated = {};

appCustom.listasClientes.direccionesRelated.mainView = {
    'url':function(id){
        return 'listasClientes/direccionesRelatedMain/' + id;
    }
};

appCustom.listasClientes.direccionesRelated.INDEX = {'url':appCustom.REST_URL + 'listasDireccionesRelated', 'verb':'GET'};
appCustom.listasClientes.direccionesRelated.STORE = {'url':appCustom.REST_URL + 'listasDireccionesRelated', 'verb':'POST'};
appCustom.listasClientes.direccionesRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasDireccionesRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.listasClientes.direccionesRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasDireccionesRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.listasClientes.direccionesRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasDireccionesRelated/' + id;
    },
    'verb': 'DELETE'
};