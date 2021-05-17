//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.banners = {};
appCustom.banners.INDEX = {'url':appCustom.REST_URL + 'banners', 'verb':'GET'};
appCustom.banners.CREATE = {'url':appCustom.REST_URL + 'banners/create', 'verb':'GET'};
appCustom.banners.STORE = {'url':appCustom.REST_URL + 'banners', 'verb':'POST'};
appCustom.banners.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.banners.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners/' + id;
    },
    'verb': 'PUT'
};
appCustom.banners.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners/' + id;
    },
    'verb': 'DELETE'
};
appCustom.banners.UPLOAD = {'url':appCustom.REST_URL + 'banners/upload', 'verb':'POST'};