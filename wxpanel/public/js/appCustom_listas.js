//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.listas = {};
appCustom.listas_slider = {};
appCustom.listas.INDEX = {'url':appCustom.REST_URL + 'listas', 'verb':'GET'};
appCustom.listas.CREATE = {'url':appCustom.REST_URL + 'listas/create', 'verb':'GET'};
appCustom.listas.STORE = {'url':appCustom.REST_URL + 'listas', 'verb':'POST'};
appCustom.listas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.listas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listas/' + id;
    },
    'verb': 'PUT'
};
appCustom.listas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listas/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.listas.image = {};

appCustom.listas.image.mainView = {
    'url':function(id){
        return 'listas/imageMain/' + id;
    }
};

appCustom.listas.image.INDEX = {'url':appCustom.REST_URL + 'listasImage', 'verb':'GET'};
appCustom.listas.image.STORE = {'url':appCustom.REST_URL + 'listasImage', 'verb':'POST'};
appCustom.listas.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.listas.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.listas.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasImage/' + id;
    },
    'verb': 'DELETE'
};

//Listas productos related
appCustom.listas.productosRelated = {};

appCustom.listas.productosRelated.mainView = {
    'url':function(id){
        return 'listas/productosRelatedMain/' + id;
    }
};

appCustom.listas.productosRelated.INDEX = {'url':appCustom.REST_URL + 'listasProductosRelated', 'verb':'GET'};
appCustom.listas.productosRelated.STORE = {'url':appCustom.REST_URL + 'listasProductosRelated', 'verb':'POST'};
appCustom.listas.productosRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasProductosRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.listas.productosRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasProductosRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.listas.productosRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'listasProductosRelated/' + id;
    },
    'verb': 'DELETE'
};

// Productos
appCustom.listas.productos = {};
appCustom.listas.productos.INDEX = {'url':appCustom.REST_URL + 'productos', 'verb':'GET'};