//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.bannersTipos = {};
appCustom.bannersTipos.INDEX = {'url':appCustom.REST_URL + 'bannersTipos', 'verb':'GET'};
appCustom.bannersTipos.CREATE = {'url':appCustom.REST_URL + 'bannersTipos/create', 'verb':'GET'};
appCustom.bannersTipos.STORE = {'url':appCustom.REST_URL + 'bannersTipos', 'verb':'POST'};
appCustom.bannersTipos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersTipos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.bannersTipos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersTipos/' + id;
    },
    'verb': 'PUT'
};
appCustom.bannersTipos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersTipos/' + id;
    },
    'verb': 'DELETE'
};