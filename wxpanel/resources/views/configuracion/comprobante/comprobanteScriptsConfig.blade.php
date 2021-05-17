<script type="text/javascript">
		
//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.comprobante = {};
appCustom.comprobante.INDEX = {'url':appCustom.REST_URL + 'comprobante', 'verb':'GET'};
appCustom.comprobante.CREATE = {'url':appCustom.REST_URL + 'comprobante/create', 'verb':'GET'};
appCustom.comprobante.STORE = {'url':appCustom.REST_URL + 'comprobante', 'verb':'POST'};
appCustom.comprobante.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobante/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.comprobante.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobante/' + id;
    },
    'verb': 'PUT'
};
appCustom.comprobante.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobante/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.comprobante.image = {};

appCustom.comprobante.image.mainView = {
    'url':function(id){
        return 'comprobante/imageMain/' + id;
    }
};

appCustom.comprobante.image.INDEX = {'url':appCustom.REST_URL + 'comprobanteImage', 'verb':'GET'};
appCustom.comprobante.image.STORE = {'url':appCustom.REST_URL + 'comprobanteImage', 'verb':'POST'};
appCustom.comprobante.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobanteImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.comprobante.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobanteImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.comprobante.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'comprobanteImage/' + id;
    },
    'verb': 'DELETE'
};
    
    
</script>

	
