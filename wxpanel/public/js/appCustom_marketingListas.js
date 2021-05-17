//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.marketingListas = {};
appCustom.marketingListas.INDEX = {'url':appCustom.REST_URL + 'marketingListas', 'verb':'GET'};
appCustom.marketingListas.CREATE = {'url':appCustom.REST_URL + 'marketingListas/create', 'verb':'GET'};
appCustom.marketingListas.STORE = {'url':appCustom.REST_URL + 'marketingListas', 'verb':'POST'};
appCustom.marketingListas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.marketingListas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListas/' + id;
    },
    'verb': 'PUT'
};
appCustom.marketingListas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListas/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.marketingListas.image = {};

appCustom.marketingListas.image.mainView = {
    'url':function(id){
        return 'marketingListas/imageMain/' + id;
    }
};

appCustom.marketingListas.image.INDEX = {'url':appCustom.REST_URL + 'marketingListasImage', 'verb':'GET'};
appCustom.marketingListas.image.STORE = {'url':appCustom.REST_URL + 'marketingListasImage', 'verb':'POST'};
appCustom.marketingListas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.marketingListas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.marketingListas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingListasImage/' + id;
    },
    'verb': 'DELETE'
};

// personas related
appCustom.marketingListas.personasRelated = {};

appCustom.marketingListas.personasRelated.mainView = {
    'url':function(id){
        return 'marketingListas/personasRelatedMain/' + id;
    }
};

appCustom.marketingListas.personasRelated.INDEX = {'url':appCustom.REST_URL + 'personasRelated', 'verb':'GET'};
appCustom.marketingListas.personasRelated.STORE = {'url':appCustom.REST_URL + 'personasRelated', 'verb':'POST'};
appCustom.marketingListas.personasRelated.EDIT = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.marketingListas.personasRelated.UPDATE = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.marketingListas.personasRelated.DELETE = {
    'url':function(id) {
        return appCustom.REST_URL + 'personasRelated/' + id;
    },
    'verb': 'DELETE'
};
appCustom.marketingListas.personasRelated.QUITARPERSONAS = {'url':appCustom.REST_URL + 'personasRelated/quitarPersona', 'verb':'POST'};
