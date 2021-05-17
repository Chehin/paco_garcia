//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.bannersPosiciones = {};
appCustom.bannersPosiciones.INDEX = {'url':appCustom.REST_URL + 'bannersPosiciones', 'verb':'GET'};
appCustom.bannersPosiciones.CREATE = {'url':appCustom.REST_URL + 'bannersPosiciones/create', 'verb':'GET'};
appCustom.bannersPosiciones.STORE = {'url':appCustom.REST_URL + 'bannersPosiciones', 'verb':'POST'};
appCustom.bannersPosiciones.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersPosiciones/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.bannersPosiciones.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersPosiciones/' + id;
    },
    'verb': 'PUT'
};
appCustom.bannersPosiciones.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'bannersPosiciones/' + id;
    },
    'verb': 'DELETE'
};