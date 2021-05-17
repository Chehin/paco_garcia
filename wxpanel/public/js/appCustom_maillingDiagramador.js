//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingDiagramador = {};
appCustom.maillingDiagramador.INDEX = {'url':appCustom.REST_URL + 'maillingDiagramador', 'verb':'GET'};
appCustom.maillingDiagramador.CREATE = {'url':appCustom.REST_URL + 'maillingDiagramador/create', 'verb':'GET'};
appCustom.maillingDiagramador.STORE = {'url':appCustom.REST_URL + 'maillingDiagramador', 'verb':'POST'};
appCustom.maillingDiagramador.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramador/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingDiagramador.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramador/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingDiagramador.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramador/' + id;
    },
    'verb': 'DELETE'
};
//language
appCustom.maillingDiagramador.language = {};

appCustom.maillingDiagramador.language.mainView = {
    'url':function(id){
        return 'note/languageMain/maillingDiagramador/' + id;
    }
};

appCustom.maillingDiagramador.language.INDEX = {'url':appCustom.REST_URL + 'maillingDiagramadorNoteLanguage', 'verb':'GET'};
appCustom.maillingDiagramador.language.STORE = {'url':appCustom.REST_URL + 'maillingDiagramadorNoteLanguage', 'verb':'POST'};
appCustom.maillingDiagramador.language.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteLanguage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingDiagramador.language.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteLanguage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingDiagramador.language.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteLanguage/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingDiagramador.image = {};

appCustom.maillingDiagramador.image.mainView = {
    'url':function(id){
        return 'maillingDiagramador/imageMain/' + id;
    }
};

appCustom.maillingDiagramador.image.INDEX = {'url':appCustom.REST_URL + 'maillingDiagramadorImage', 'verb':'GET'};
appCustom.maillingDiagramador.image.STORE = {'url':appCustom.REST_URL + 'maillingDiagramadorImage', 'verb':'POST'};
appCustom.maillingDiagramador.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingDiagramador.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingDiagramador.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.maillingDiagramador.noteRelated = {};

appCustom.maillingDiagramador.noteRelated.mainView = {
    'url':function(id){
        return 'maillingDiagramador/noteRelatedMain/' + id;
    }
};

appCustom.maillingDiagramador.noteRelated.INDEX = {'url':appCustom.REST_URL + 'maillingDiagramadorNoteRelated', 'verb':'GET'};
appCustom.maillingDiagramador.noteRelated.STORE = {'url':appCustom.REST_URL + 'maillingDiagramadorNoteRelated', 'verb':'POST'};
appCustom.maillingDiagramador.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingDiagramador.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingDiagramador.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'maillingDiagramadorNoteRelated/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.maillingDiagramador.note = {};
appCustom.maillingDiagramador.note.INDEX = {'url':appCustom.REST_URL + 'note', 'verb':'GET'};

