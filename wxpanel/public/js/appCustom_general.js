//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.general = {};
appCustom.general.INDEX = {'url':appCustom.REST_URL + 'general', 'verb':'GET'};
appCustom.general.CREATE = {'url':appCustom.REST_URL + 'general/create', 'verb':'GET'};
appCustom.general.STORE = {'url':appCustom.REST_URL + 'general', 'verb':'POST'};
appCustom.general.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'general/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.general.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'general/' + id;
    },
    'verb': 'PUT'
};
