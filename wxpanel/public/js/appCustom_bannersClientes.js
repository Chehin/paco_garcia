//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.bannersClientes = {};
appCustom.bannersClientes.INDEX = {'url':appCustom.REST_URL + 'bannersClientes', 'verb':'GET'};
appCustom.bannersClientes.CREATE = {'url':appCustom.REST_URL + 'bannersClientes/create', 'verb':'GET'};
appCustom.bannersClientes.STORE = {'url':appCustom.REST_URL + 'bannersClientes', 'verb':'POST'};
appCustom.bannersClientes.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersClientes/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.bannersClientes.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersClientes/' + id;
    },
    'verb': 'PUT'
};
appCustom.bannersClientes.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersClientes/' + id;
    },
    'verb': 'DELETE'
};