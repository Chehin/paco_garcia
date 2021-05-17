//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.monedas = {};
appCustom.monedas.INDEX = {'url':appCustom.REST_URL + 'monedas', 'verb':'GET'};
appCustom.monedas.CREATE = {'url':appCustom.REST_URL + 'monedas/create', 'verb':'GET'};
appCustom.monedas.STORE = {'url':appCustom.REST_URL + 'monedas', 'verb':'POST'};
appCustom.monedas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'monedas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.monedas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'monedas/' + id;
    },
    'verb': 'PUT'
};
appCustom.monedas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'monedas/' + id;
    },
    'verb': 'DELETE'
}; 