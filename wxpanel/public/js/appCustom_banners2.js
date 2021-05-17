//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.banners2 = {};
appCustom.banners2.INDEX = {'url':appCustom.REST_URL + 'banners2', 'verb':'GET'};
appCustom.banners2.CREATE = {'url':appCustom.REST_URL + 'banners2/create', 'verb':'GET'};
appCustom.banners2.STORE = {'url':appCustom.REST_URL + 'banners2', 'verb':'POST'};
appCustom.banners2.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.banners2.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2/' + id;
    },
    'verb': 'PUT'
};
appCustom.banners2.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'banners2/' + id;
    },
    'verb': 'DELETE'
};
appCustom.banners2.UPLOAD = {'url':appCustom.REST_URL + 'banners2/upload', 'verb':'POST'};

// personas related
appCustom.banners2.personasRelated = {};

appCustom.banners2.personasRelated.mainView = {
    'url':function(id){
        return 'banners2/personasRelatedMain/' + id;
    }
};

appCustom.banners2.personasRelated.INDEX = {'url':appCustom.REST_URL + 'personasRelated', 'verb':'GET'};
appCustom.banners2.personasRelated.STORE = {'url':appCustom.REST_URL + 'personasRelated', 'verb':'POST'};
appCustom.banners2.personasRelated.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.banners2.personasRelated.UPDATE = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.banners2.personasRelated.DELETE = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id;
    },
    'verb': 'DELETE'
};
appCustom.banners2.personasRelated.QUITARPERSONAS = {'url':appCustom.REST_URL + 'personasRelated/quitarPersona', 'verb':'POST'};