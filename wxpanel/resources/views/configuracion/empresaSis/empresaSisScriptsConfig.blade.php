<script type="text/javascript">
		
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.empresaSis = {};
appCustom.empresaSis.INDEX = {'url':appCustom.REST_URL + 'empresaSis', 'verb':'GET'};
appCustom.empresaSis.CREATE = {'url':appCustom.REST_URL + 'empresaSis/create', 'verb':'GET'};
appCustom.empresaSis.STORE = {'url':appCustom.REST_URL + 'empresaSis', 'verb':'POST'};
appCustom.empresaSis.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSis/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.empresaSis.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSis/' + id;
    },
    'verb': 'PUT'
};
appCustom.empresaSis.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSis/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.empresaSis.image = {};

appCustom.empresaSis.image.mainView = {
    'url':function(id){
        return 'empresaSis/imageMain/' + id;
    }
};

appCustom.empresaSis.image.INDEX = {'url':appCustom.REST_URL + 'empresaSisImage', 'verb':'GET'};
appCustom.empresaSis.image.STORE = {'url':appCustom.REST_URL + 'empresaSisImage', 'verb':'POST'};
appCustom.empresaSis.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSisImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.empresaSis.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSisImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.empresaSis.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'empresaSisImage/' + id;
    },
    'verb': 'DELETE'
};
    
    
</script>

	
