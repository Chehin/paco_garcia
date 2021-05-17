//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.agencia = {};
appCustom.agencia.INDEX = {'url':appCustom.REST_URL + 'agencia', 'verb':'GET'};
appCustom.agencia.CREATE = {'url':appCustom.REST_URL + 'agencia/create', 'verb':'GET'};
appCustom.agencia.STORE = {'url':appCustom.REST_URL + 'agencia', 'verb':'POST'};
appCustom.agencia.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agencia/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.agencia.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agencia/' + id;
    },
    'verb': 'PUT'
};
appCustom.agencia.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agencia/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.agencia.image = {};

appCustom.agencia.image.mainView = {
    'url':function(id){
        return 'agencia/imageMain/' + id;
    }
};

appCustom.agencia.image.INDEX = {'url':appCustom.REST_URL + 'agenciaImage', 'verb':'GET'};
appCustom.agencia.image.STORE = {'url':appCustom.REST_URL + 'agenciaImage', 'verb':'POST'};
appCustom.agencia.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.agencia.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.agencia.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaImage/' + id;
    },
    'verb': 'DELETE'
};

//publicaciones related

appCustom.agencia.agenciaPublicRelated = {};

appCustom.agencia.agenciaPublicRelated.mainView = {
    'url':function(id){
        return 'agencia/agenciaPublicRelatedMain/' + id;
    }
};

appCustom.agencia.agenciaPublicRelated.INDEX = {'url':appCustom.REST_URL + 'agenciaPublicRelated', 'verb':'GET'};
appCustom.agencia.agenciaPublicRelated.STORE = {'url':appCustom.REST_URL + 'agenciaPublicRelated', 'verb':'POST'};
appCustom.agencia.agenciaPublicRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaPublicRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.agencia.agenciaPublicRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaPublicRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.agencia.agenciaPublicRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'agenciaPublicRelated/' + id;
    },
    'verb': 'DELETE'
};

appCustom.agencia.agenciaPublicRelated.UPDATE_INLINE = {
    'url': appCustom.REST_URL + 'agenciaPublicRelatedUpdateInline'
};
