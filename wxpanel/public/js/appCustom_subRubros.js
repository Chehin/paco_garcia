//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.subRubros = {};
appCustom.subRubros.INDEX = {'url':appCustom.REST_URL + 'subRubros', 'verb':'GET'};
appCustom.subRubros.CREATE = {'url':appCustom.REST_URL + 'subRubros/create', 'verb':'GET'};
appCustom.subRubros.STORE = {'url':appCustom.REST_URL + 'subRubros', 'verb':'POST'};
appCustom.subRubros.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subRubros/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.subRubros.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subRubros/' + id;
    },
    'verb': 'PUT'
};
appCustom.subRubros.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subRubros/' + id;
    },
    'verb': 'DELETE'
};
appCustom.subRubros.OBTENER_SUBRUBROS = {'url':appCustom.REST_URL + 'obtenerSubrubros', 'verb':'GET'};