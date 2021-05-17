//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.productos = {};
appCustom.productos_slider = {};
appCustom.productos.INDEX = {'url':appCustom.REST_URL + 'productos', 'verb':'GET'};
appCustom.productos.CREATE = {'url':appCustom.REST_URL + 'productos/create', 'verb':'GET'};
appCustom.productos.STORE = {'url':appCustom.REST_URL + 'productos', 'verb':'POST'};
appCustom.productos.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.productos.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productos/' + id;
    },
    'verb': 'PUT'
};
appCustom.productos.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productos/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.productos.image = {};

appCustom.productos.image.mainView = {
    'url':function(id){
        return 'productos/imageMain/' + id;
    }
};

appCustom.productos.image.INDEX = {'url':appCustom.REST_URL + 'productosImage', 'verb':'GET'};
appCustom.productos.image.STORE = {'url':appCustom.REST_URL + 'productosImage', 'verb':'POST'};
appCustom.productos.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.productos.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.productos.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosImage/' + id;
    },
    'verb': 'DELETE'
};

//precios related

appCustom.productos.preciosRelated = {};

appCustom.productos.preciosRelated.mainView = {
    'url':function(id){
        return 'productos/preciosRelatedMain/' + id;
    }
};

appCustom.productos.preciosRelated.INDEX = {'url':appCustom.REST_URL + 'preciosRelated', 'verb':'GET'};
appCustom.productos.preciosRelated.STORE = {'url':appCustom.REST_URL + 'preciosRelated', 'verb':'POST'};
appCustom.productos.preciosRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'preciosRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.productos.preciosRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'preciosRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.productos.preciosRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'preciosRelated/' + id;
    },
    'verb': 'DELETE'
};
appCustom.productos.preciosRelated.EDIT_IN_LINE = {'url':appCustom.REST_URL + 'preciosRelated/editInLine', 'verb':'POST'};

//productos productos related
appCustom.productos.productosRelated = {};

appCustom.productos.productosRelated.mainView = {
    'url':function(id){
        return 'productos/productosRelatedMain/' + id;
    }
};

appCustom.productos.productosRelated.INDEX = {'url':appCustom.REST_URL + 'productosProductosRelated', 'verb':'GET'};
appCustom.productos.productosRelated.STORE = {'url':appCustom.REST_URL + 'productosProductosRelated', 'verb':'POST'};
appCustom.productos.productosRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.productos.productosRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.productos.productosRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelated/' + id;
    },
    'verb': 'DELETE'
};

//productos color productos related
appCustom.productos.productosRelatedColor = {};

appCustom.productos.productosRelatedColor.mainView = {
    'url':function(id){
        return 'productos/productosRelatedColor/' + id;
    }
};

appCustom.productos.productosRelatedColor.INDEX = {'url':appCustom.REST_URL + 'productosProductosRelatedColor', 'verb':'GET'};
appCustom.productos.productosRelatedColor.STORE = {'url':appCustom.REST_URL + 'productosProductosRelatedColor', 'verb':'POST'};
appCustom.productos.productosRelatedColor.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelatedColor/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.productos.productosRelatedColor.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelatedColor/' + id;
    },
    'verb': 'PUT'
};

appCustom.productos.productosRelatedColor.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosProductosRelatedColor/' + id;
    },
    'verb': 'DELETE'
};

// Mercado libre
appCustom.productos.Meli = {};

appCustom.productos.Meli.STORE = {
    'url':function(id) {
        return appCustom.REST_URL + 'createPublicacion/' + id;
    },
    'verb': 'POST'
};
appCustom.productos.Meli.UPDATE = {
    'url':function(id) {
        return appCustom.REST_URL + 'updatePublicacion/' + id;
    },
    'verb': 'PUT'
};
appCustom.productos.Meli.SHOW = {
    'url':function(id) {
        return appCustom.REST_URL + 'verPublicacion/' + id;
    },
    'verb': 'GET'
};
appCustom.productos.Meli.DELETE = {
    'url':function(id) {
        return appCustom.REST_URL + 'deletePublicacion/' + id;
    },
    'verb': 'DELETE'
};

appCustom.productos.productosPreguntas = {};

appCustom.productos.productosPreguntas.mainView = {
    'url':function(id){
        return 'productos/preguntasRelatedMain/' + id;
    }
};

appCustom.productos.productosPreguntas.INDEX = {'url':appCustom.REST_URL + 'productosPreguntas', 'verb':'GET'};
appCustom.productos.productosPreguntas.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosPreguntas/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.productos.productosPreguntas.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosPreguntas/' + id;
    },
    'verb': 'POST'
};

appCustom.productos.productosPreguntas.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'productosPreguntas/' + id;
    },
    'verb': 'DELETE'
};


//REST REQUESTs CONFIG
appCustom.setEtiquetas = {};
appCustom.setEtiquetas.INDEX = {'url':appCustom.REST_URL + 'setEtiquetas', 'verb':'GET'};
appCustom.setEtiquetas.CREATE = {
	'url':function(parentResource){
		return appCustom.REST_URL + 'setEtiquetas/create?parentResource=' + parentResource;
	}, 
	'verb':'GET'
};
appCustom.setEtiquetas.STORE = {'url':appCustom.REST_URL + 'setEtiquetas', 'verb':'POST'};