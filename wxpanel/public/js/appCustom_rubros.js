//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.rubros = {};
appCustom.rubros.INDEX = {'url':appCustom.REST_URL + 'rubros', 'verb':'GET'};
appCustom.rubros.CREATE = {'url':appCustom.REST_URL + 'rubros/create', 'verb':'GET'};
appCustom.rubros.STORE = {'url':appCustom.REST_URL + 'rubros', 'verb':'POST'};
appCustom.rubros.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubros/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.rubros.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubros/' + id;
    },
    'verb': 'PUT'
};
appCustom.rubros.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubros/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.rubros.image = {};

appCustom.rubros.image.mainView = {
    'url':function(id){
        return 'rubros/imageMain/' + id;
    }
};

appCustom.rubros.image.INDEX = {'url':appCustom.REST_URL + 'rubrosImage', 'verb':'GET'};
appCustom.rubros.image.STORE = {'url':appCustom.REST_URL + 'rubrosImage', 'verb':'POST'};
appCustom.rubros.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubrosImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.rubros.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubrosImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.rubros.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'rubrosImage/' + id;
    },
    'verb': 'DELETE'
};