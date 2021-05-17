//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.newsletter = {};
appCustom.newsletter.INDEX = {'url':appCustom.REST_URL + 'newsletter', 'verb':'GET'};
appCustom.newsletter.CREATE = {'url':appCustom.REST_URL + 'newsletter/create', 'verb':'GET'};
appCustom.newsletter.STORE = {'url':appCustom.REST_URL + 'newsletter', 'verb':'POST'};
appCustom.newsletter.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletter/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.newsletter.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletter/' + id;
    },
    'verb': 'PUT'
};
appCustom.newsletter.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletter/' + id;
    },
    'verb': 'DELETE'
};
//language
appCustom.newsletter.language = {};

appCustom.newsletter.language.mainView = {
    'url':function(id){
        return 'note/languageMain/newsletter/' + id;
    }
};

appCustom.newsletter.language.INDEX = {'url':appCustom.REST_URL + 'newsletterNoteLanguage', 'verb':'GET'};
appCustom.newsletter.language.STORE = {'url':appCustom.REST_URL + 'newsletterNoteLanguage', 'verb':'POST'};
appCustom.newsletter.language.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteLanguage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.newsletter.language.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteLanguage/' + id;
    },
    'verb': 'PUT'
};

appCustom.newsletter.language.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteLanguage/' + id;
    },
    'verb': 'DELETE'
};
//image
appCustom.newsletter.image = {};

appCustom.newsletter.image.mainView = {
    'url':function(id){
        return 'newsletter/imageMain/' + id;
    }
};

appCustom.newsletter.image.INDEX = {'url':appCustom.REST_URL + 'newsletterImage', 'verb':'GET'};
appCustom.newsletter.image.STORE = {'url':appCustom.REST_URL + 'newsletterImage', 'verb':'POST'};
appCustom.newsletter.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.newsletter.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.newsletter.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.newsletter.noteRelated = {};

appCustom.newsletter.noteRelated.mainView = {
    'url':function(id){
        return 'newsletter/noteRelatedMain/' + id;
    }
};

appCustom.newsletter.noteRelated.INDEX = {'url':appCustom.REST_URL + 'newsletterNoteRelated', 'verb':'GET'};
appCustom.newsletter.noteRelated.STORE = {'url':appCustom.REST_URL + 'newsletterNoteRelated', 'verb':'POST'};
appCustom.newsletter.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.newsletter.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.newsletter.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsletterNoteRelated/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.newsletter.note = {};
appCustom.newsletter.note.INDEX = {'url':appCustom.REST_URL + 'note', 'verb':'GET'};