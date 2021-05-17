//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.banners2Tipos = {};
appCustom.banners2Tipos.INDEX = {'url':appCustom.REST_URL + 'banners2Tipos', 'verb':'GET'};
appCustom.banners2Tipos.CREATE = {'url':appCustom.REST_URL + 'banners2Tipos/create', 'verb':'GET'};
appCustom.banners2Tipos.STORE = {'url':appCustom.REST_URL + 'banners2Tipos', 'verb':'POST'};
appCustom.banners2Tipos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Tipos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.banners2Tipos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Tipos/' + id;
    },
    'verb': 'PUT'
};
appCustom.banners2Tipos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Tipos/' + id;
    },
    'verb': 'DELETE'
};