//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.deportes = {};
appCustom.deportes.INDEX = {'url':appCustom.REST_URL + 'deportes', 'verb':'GET'};
appCustom.deportes.CREATE = {'url':appCustom.REST_URL + 'deportes/create', 'verb':'GET'};
appCustom.deportes.STORE = {'url':appCustom.REST_URL + 'deportes', 'verb':'POST'};
appCustom.deportes.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportes/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.deportes.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportes/' + id;
    },
    'verb': 'PUT'
};
appCustom.deportes.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportes/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.deportes.image = {};

appCustom.deportes.image.mainView = {
    'url':function(id){
        return 'deportes/imageMain/' + id;
    }
};

appCustom.deportes.image.INDEX = {'url':appCustom.REST_URL + 'deportesImage', 'verb':'GET'};
appCustom.deportes.image.STORE = {'url':appCustom.REST_URL + 'deportesImage', 'verb':'POST'};
appCustom.deportes.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportesImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.deportes.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportesImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.deportes.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'deportesImage/' + id;
    },
    'verb': 'DELETE'
};