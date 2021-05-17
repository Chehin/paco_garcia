//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.tipos_eventos = {};
appCustom.tipos_eventos.INDEX = {'url':appCustom.REST_URL + 'tipos_eventos', 'verb':'GET'};
appCustom.tipos_eventos.CREATE = {'url':appCustom.REST_URL + 'tipos_eventos/create', 'verb':'GET'};
appCustom.tipos_eventos.STORE = {'url':appCustom.REST_URL + 'tipos_eventos', 'verb':'POST'};
appCustom.tipos_eventos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'tipos_eventos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.tipos_eventos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'tipos_eventos/' + id;
    },
    'verb': 'PUT'
};
appCustom.tipos_eventos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'tipos_eventos/' + id;
    },
    'verb': 'DELETE'
};