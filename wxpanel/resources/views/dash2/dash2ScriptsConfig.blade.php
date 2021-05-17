<script>
	

//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.dash2 = {};
appCustom.dash2.INDEX = {'url':appCustom.REST_URL + 'dash2', 'verb':'GET'};
appCustom.dash2.CREATE = {'url':appCustom.REST_URL + 'dash2/create', 'verb':'GET'};
appCustom.dash2.STORE = {'url':appCustom.REST_URL + 'dash2', 'verb':'POST'};
appCustom.dash2.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.dash2.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2/' + id;
    },
    'verb': 'PUT'
};
appCustom.dash2.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2/' + id;
    },
    'verb': 'DELETE'
};
appCustom.dash2.OBTENER_PROVINCIAS = {'url':appCustom.REST_URL + 'obtenerProvincias', 'verb':'GET'};

//image
appCustom.dash2.image = {};

appCustom.dash2.image.mainView = {
    'url':function(id){
        return 'dash2/imageMain/' + id;
    }
};

appCustom.dash2.image.INDEX = {'url':appCustom.REST_URL + 'dash2Image', 'verb':'GET'};
appCustom.dash2.image.STORE = {'url':appCustom.REST_URL + 'dash2Image', 'verb':'POST'};
appCustom.dash2.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2Image/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.dash2.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2Image/' + id;
    },
    'verb': 'PUT'
};

appCustom.dash2.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'dash2Image/' + id;
    },
    'verb': 'DELETE'
};

</script>