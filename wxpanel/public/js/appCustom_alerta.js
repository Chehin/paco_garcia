//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.alerta = {};
appCustom.alerta.INDEX = {'url':appCustom.REST_URL + 'alerta', 'verb':'GET'};
appCustom.alerta.CREATE = {'url':appCustom.REST_URL + 'alerta/create', 'verb':'GET'};
appCustom.alerta.STORE = {'url':appCustom.REST_URL + 'alerta', 'verb':'POST'};
appCustom.alerta.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'alerta/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.alerta.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'alerta/' + id;
    },
    'verb': 'PUT'
};
appCustom.alerta.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'alerta/' + id;
    },
    'verb': 'DELETE'
};
