//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.etiquetasBlog = {};
appCustom.etiquetasBlog.INDEX = {'url':appCustom.REST_URL + 'etiquetasBlog', 'verb':'GET'};
appCustom.etiquetasBlog.CREATE = {'url':appCustom.REST_URL + 'etiquetasBlog/create', 'verb':'GET'};
appCustom.etiquetasBlog.STORE = {'url':appCustom.REST_URL + 'etiquetasBlog', 'verb':'POST'};
appCustom.etiquetasBlog.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlog/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.etiquetasBlog.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlog/' + id;
    },
    'verb': 'PUT'
};
appCustom.etiquetasBlog.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlog/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.etiquetasBlog.image = {};

appCustom.etiquetasBlog.image.mainView = {
    'url':function(id){
        return 'etiquetasBlog/imageMain/' + id;
    }
};

appCustom.etiquetasBlog.image.INDEX = {'url':appCustom.REST_URL + 'etiquetasBlogImage', 'verb':'GET'};
appCustom.etiquetasBlog.image.STORE = {'url':appCustom.REST_URL + 'etiquetasBlogImage', 'verb':'POST'};
appCustom.etiquetasBlog.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlogImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.etiquetasBlog.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlogImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.etiquetasBlog.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'etiquetasBlogImage/' + id;
    },
    'verb': 'DELETE'
};