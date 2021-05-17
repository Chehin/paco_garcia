<script>
	

//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.dash3 = {};
appCustom.dash3.INDEX = {'url':appCustom.REST_URL + 'dash3', 'verb':'GET'};
appCustom.dash3.CREATE = {'url':appCustom.REST_URL + 'dash3/create', 'verb':'GET'};
appCustom.dash3.STORE = {'url':appCustom.REST_URL + 'dash3', 'verb':'POST'};
appCustom.dash3.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.dash3.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3/' + id;
    },
    'verb': 'PUT'
};
appCustom.dash3.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3/' + id;
    },
    'verb': 'DELETE'
};
appCustom.dash3.OBTENER_PROVINCIAS = {'url':appCustom.REST_URL + 'obtenerProvincias', 'verb':'GET'};

//image
appCustom.dash3.image = {};

appCustom.dash3.image.mainView = {
    'url':function(id){
        return 'dash3/imageMain/' + id;
    }
};

appCustom.dash3.image.INDEX = {'url':appCustom.REST_URL + 'dash3Image', 'verb':'GET'};
appCustom.dash3.image.STORE = {'url':appCustom.REST_URL + 'dash3Image', 'verb':'POST'};
appCustom.dash3.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3Image/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.dash3.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3Image/' + id;
    },
    'verb': 'PUT'
};

appCustom.dash3.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash3Image/' + id;
    },
    'verb': 'DELETE'
};

</script>