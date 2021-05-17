//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.marketingPersonas = {};
appCustom.marketingPersonas.INDEX = {'url':appCustom.REST_URL + 'marketingPersonas', 'verb':'GET'};
appCustom.marketingPersonas.CREATE = {'url':appCustom.REST_URL + 'marketingPersonas/create', 'verb':'GET'};
appCustom.marketingPersonas.STORE = {'url':appCustom.REST_URL + 'marketingPersonas', 'verb':'POST'};
appCustom.marketingPersonas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.marketingPersonas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonas/' + id;
    },
    'verb': 'PUT'
};
appCustom.marketingPersonas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonas/' + id;
    },
    'verb': 'DELETE'
};
appCustom.marketingPersonas.OBTENER_PROVINCIAS = {'url':appCustom.REST_URL + 'obtenerProvincias', 'verb':'GET'};

//image
appCustom.marketingPersonas.image = {};

appCustom.marketingPersonas.image.mainView = {
    'url':function(id){
        return 'marketingPersonas/imageMain/' + id;
    }
};

appCustom.marketingPersonas.image.INDEX = {'url':appCustom.REST_URL + 'marketingPersonasImage', 'verb':'GET'};
appCustom.marketingPersonas.image.STORE = {'url':appCustom.REST_URL + 'marketingPersonasImage', 'verb':'POST'};
appCustom.marketingPersonas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.marketingPersonas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.marketingPersonas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingPersonasImage/' + id;
    },
    'verb': 'DELETE'
};