//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.etiquetas = {};
appCustom.etiquetas.INDEX = {'url':appCustom.REST_URL + 'etiquetas', 'verb':'GET'};
appCustom.etiquetas.CREATE = {'url':appCustom.REST_URL + 'etiquetas/create', 'verb':'GET'};
appCustom.etiquetas.STORE = {'url':appCustom.REST_URL + 'etiquetas', 'verb':'POST'};
appCustom.etiquetas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.etiquetas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetas/' + id;
    },
    'verb': 'PUT'
};
appCustom.etiquetas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetas/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.etiquetas.image = {};

appCustom.etiquetas.image.mainView = {
    'url':function(id){
        return 'etiquetas/imageMain/' + id;
    }
};

appCustom.etiquetas.image.INDEX = {'url':appCustom.REST_URL + 'etiquetasImage', 'verb':'GET'};
appCustom.etiquetas.image.STORE = {'url':appCustom.REST_URL + 'etiquetasImage', 'verb':'POST'};
appCustom.etiquetas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.etiquetas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.etiquetas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasImage/' + id;
    },
    'verb': 'DELETE'
};