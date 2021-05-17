//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.talles = {};
appCustom.talles.INDEX = {'url':appCustom.REST_URL + 'talles', 'verb':'GET'};
appCustom.talles.CREATE = {'url':appCustom.REST_URL + 'talles/create', 'verb':'GET'};
appCustom.talles.STORE = {'url':appCustom.REST_URL + 'talles', 'verb':'POST'};
appCustom.talles.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'talles/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.talles.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'talles/' + id;
    },
    'verb': 'PUT'
};
appCustom.talles.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'talles/' + id;
    },
    'verb': 'DELETE'
};