//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.colores = {};
appCustom.colores.INDEX = {'url':appCustom.REST_URL + 'colores', 'verb':'GET'};
appCustom.colores.CREATE = {'url':appCustom.REST_URL + 'colores/create', 'verb':'GET'};
appCustom.colores.STORE = {'url':appCustom.REST_URL + 'colores', 'verb':'POST'};
appCustom.colores.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'colores/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.colores.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'colores/' + id;
    },
    'verb': 'PUT'
};
appCustom.colores.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'colores/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.colores.image = {};

appCustom.colores.image.mainView = {
    'url':function(id){
        return 'colores/imageMain/' + id;
    }
};

appCustom.colores.image.INDEX = {'url':appCustom.REST_URL + 'coloresImage', 'verb':'GET'};
appCustom.colores.image.STORE = {'url':appCustom.REST_URL + 'coloresImage', 'verb':'POST'};
appCustom.colores.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'coloresImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.colores.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'coloresImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.colores.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'coloresImage/' + id;
    },
    'verb': 'DELETE'
};
