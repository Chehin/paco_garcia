//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.marcas = {};
appCustom.marcas.INDEX = {'url':appCustom.REST_URL + 'marcas', 'verb':'GET'};
appCustom.marcas.CREATE = {'url':appCustom.REST_URL + 'marcas/create', 'verb':'GET'};
appCustom.marcas.STORE = {'url':appCustom.REST_URL + 'marcas', 'verb':'POST'};
appCustom.marcas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.marcas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcas/' + id;
    },
    'verb': 'PUT'
};
appCustom.marcas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcas/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.marcas.image = {};

appCustom.marcas.image.mainView = {
    'url':function(id){
        return 'marcas/imageMain/' + id;
    }
};

appCustom.marcas.image.INDEX = {'url':appCustom.REST_URL + 'marcasImage', 'verb':'GET'};
appCustom.marcas.image.STORE = {'url':appCustom.REST_URL + 'marcasImage', 'verb':'POST'};
appCustom.marcas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.marcas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.marcas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marcasImage/' + id;
    },
    'verb': 'DELETE'
};