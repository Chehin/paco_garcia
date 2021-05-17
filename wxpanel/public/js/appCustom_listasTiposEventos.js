//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.listasTiposEventos = {};
appCustom.listasTiposEventos.INDEX = {'url':appCustom.REST_URL + 'listasTiposEventos', 'verb':'GET'};
appCustom.listasTiposEventos.CREATE = {'url':appCustom.REST_URL + 'listasTiposEventos/create', 'verb':'GET'};
appCustom.listasTiposEventos.STORE = {'url':appCustom.REST_URL + 'listasTiposEventos', 'verb':'POST'};
appCustom.listasTiposEventos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.listasTiposEventos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventos/' + id;
    },
    'verb': 'PUT'
};
appCustom.listasTiposEventos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventos/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.listasTiposEventos.image = {};

appCustom.listasTiposEventos.image.mainView = {
    'url':function(id){
        return 'listasTiposEventos/imageMain/' + id;
    }
};

appCustom.listasTiposEventos.image.INDEX = {'url':appCustom.REST_URL + 'listasTiposEventosImage', 'verb':'GET'};
appCustom.listasTiposEventos.image.STORE = {'url':appCustom.REST_URL + 'listasTiposEventosImage', 'verb':'POST'};
appCustom.listasTiposEventos.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventosImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.listasTiposEventos.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventosImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.listasTiposEventos.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasTiposEventosImage/' + id;
    },
    'verb': 'DELETE'
};