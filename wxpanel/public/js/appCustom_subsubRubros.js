//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.subsubRubros = {};
appCustom.subsubRubros.INDEX = {'url':appCustom.REST_URL + 'subsubRubros', 'verb':'GET'};
appCustom.subsubRubros.CREATE = {'url':appCustom.REST_URL + 'subsubRubros/create', 'verb':'GET'};
appCustom.subsubRubros.STORE = {'url':appCustom.REST_URL + 'subsubRubros', 'verb':'POST'};
appCustom.subsubRubros.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subsubRubros/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.subsubRubros.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subsubRubros/' + id;
    },
    'verb': 'PUT'
};
appCustom.subsubRubros.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'subsubRubros/' + id;
    },
    'verb': 'DELETE'
};
appCustom.subsubRubros.OBTENER_SUBSUBRUBROS = {'url':appCustom.REST_URL + 'obtenerSubSubrubros', 'verb':'GET'};