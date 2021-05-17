//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.banners2Posiciones = {};
appCustom.banners2Posiciones.INDEX = {'url':appCustom.REST_URL + 'banners2Posiciones', 'verb':'GET'};
appCustom.banners2Posiciones.CREATE = {'url':appCustom.REST_URL + 'banners2Posiciones/create', 'verb':'GET'};
appCustom.banners2Posiciones.STORE = {'url':appCustom.REST_URL + 'banners2Posiciones', 'verb':'POST'};
appCustom.banners2Posiciones.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Posiciones/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.banners2Posiciones.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Posiciones/' + id;
    },
    'verb': 'PUT'
};
appCustom.banners2Posiciones.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2Posiciones/' + id;
    },
    'verb': 'DELETE'
};