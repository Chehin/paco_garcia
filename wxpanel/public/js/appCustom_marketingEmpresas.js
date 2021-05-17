//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.marketingEmpresas = {};
appCustom.marketingEmpresas.INDEX = {'url':appCustom.REST_URL + 'marketingEmpresas', 'verb':'GET'};
appCustom.marketingEmpresas.CREATE = {'url':appCustom.REST_URL + 'marketingEmpresas/create', 'verb':'GET'};
appCustom.marketingEmpresas.STORE = {'url':appCustom.REST_URL + 'marketingEmpresas', 'verb':'POST'};
appCustom.marketingEmpresas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.marketingEmpresas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresas/' + id;
    },
    'verb': 'PUT'
};
appCustom.marketingEmpresas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresas/' + id;
    },
    'verb': 'DELETE'
};
appCustom.marketingEmpresas.OBTENER_PROVINCIAS = {'url':appCustom.REST_URL + 'obtenerProvincias', 'verb':'GET'};

//image
appCustom.marketingEmpresas.image = {};

appCustom.marketingEmpresas.image.mainView = {
    'url':function(id){
        return 'marketingEmpresas/imageMain/' + id;
    }
};

appCustom.marketingEmpresas.image.INDEX = {'url':appCustom.REST_URL + 'marketingEmpresasImage', 'verb':'GET'};
appCustom.marketingEmpresas.image.STORE = {'url':appCustom.REST_URL + 'marketingEmpresasImage', 'verb':'POST'};
appCustom.marketingEmpresas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.marketingEmpresas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.marketingEmpresas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'marketingEmpresasImage/' + id;
    },
    'verb': 'DELETE'
};