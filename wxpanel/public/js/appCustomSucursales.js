//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.sucursales = {};
appCustom.sucursales.INDEX = {'url':appCustom.REST_URL + 'sucursales', 'verb':'GET'};
appCustom.sucursales.CREATE = {'url':appCustom.REST_URL + 'sucursales/create', 'verb':'GET'};
appCustom.sucursales.STORE = {'url':appCustom.REST_URL + 'sucursales', 'verb':'POST'};
appCustom.sucursales.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursales/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.sucursales.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursales/' + id;
    },
    'verb': 'PUT'
};
appCustom.sucursales.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursales/' + id;
    },
    'verb': 'DELETE'
};
//language
appCustom.sucursales.language = {};

appCustom.sucursales.language.mainView = {
    'url':function(id){
        return 'note/languageMain/sucursales/' + id;
    }
};

appCustom.sucursales.language.INDEX = {'url':appCustom.REST_URL + 'sucursalesNoteLanguage', 'verb':'GET'};
appCustom.sucursales.language.STORE = {'url':appCustom.REST_URL + 'sucursalesNoteLanguage', 'verb':'POST'};
appCustom.sucursales.language.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteLanguage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sucursales.language.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteLanguage/' + id;
    },
    'verb': 'PUT'
};

appCustom.sucursales.language.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteLanguage/' + id;
    },
    'verb': 'DELETE'
};
//image
appCustom.sucursales.image = {};

appCustom.sucursales.image.mainView = {
    'url':function(id){
        return 'sucursales/imageMain/' + id;
    }
};

appCustom.sucursales.image.INDEX = {'url':appCustom.REST_URL + 'sucursalesImage', 'verb':'GET'};
appCustom.sucursales.image.STORE = {'url':appCustom.REST_URL + 'sucursalesImage', 'verb':'POST'};
appCustom.sucursales.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sucursales.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.sucursales.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.sucursales.noteRelated = {};

appCustom.sucursales.noteRelated.mainView = {
    'url':function(id){
        return 'sucursales/noteRelatedMain/' + id;
    }
};

appCustom.sucursales.noteRelated.INDEX = {'url':appCustom.REST_URL + 'sucursalesNoteRelated', 'verb':'GET'};
appCustom.sucursales.noteRelated.STORE = {'url':appCustom.REST_URL + 'sucursalesNoteRelated', 'verb':'POST'};
appCustom.sucursales.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.sucursales.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.sucursales.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sucursalesNoteRelated/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.sucursales.note = {};
appCustom.sucursales.note.INDEX = {'url':appCustom.REST_URL + 'note', 'verb':'GET'};

