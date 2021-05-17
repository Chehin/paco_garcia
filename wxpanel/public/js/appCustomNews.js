//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.news = {};
appCustom.news.INDEX = {'url':appCustom.REST_URL + 'news', 'verb':'GET'};
appCustom.news.CREATE = {'url':appCustom.REST_URL + 'news/create', 'verb':'GET'};
appCustom.news.STORE = {'url':appCustom.REST_URL + 'news', 'verb':'POST'};
appCustom.news.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'news/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.news.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'news/' + id;
    },
    'verb': 'PUT'
};
appCustom.news.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'news/' + id;
    },
    'verb': 'DELETE'
};
//language
appCustom.news.language = {};

appCustom.news.language.mainView = {
    'url':function(id){
        return 'note/languageMain/news/' + id;
    }
};

appCustom.news.language.INDEX = {'url':appCustom.REST_URL + 'newsNoteLanguage', 'verb':'GET'};
appCustom.news.language.STORE = {'url':appCustom.REST_URL + 'newsNoteLanguage', 'verb':'POST'};
appCustom.news.language.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteLanguage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.news.language.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteLanguage/' + id;
    },
    'verb': 'PUT'
};

appCustom.news.language.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteLanguage/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.news.image = {};

appCustom.news.image.mainView = {
    'url':function(id){
        return 'news/imageMain/' + id;
    }
};

appCustom.news.image.INDEX = {'url':appCustom.REST_URL + 'newsImage', 'verb':'GET'};
appCustom.news.image.STORE = {'url':appCustom.REST_URL + 'newsImage', 'verb':'POST'};
appCustom.news.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.news.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.news.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.news.noteRelated = {};

appCustom.news.noteRelated.mainView = {
    'url':function(id){
        return 'news/noteRelatedMain/' + id;
    }
};

appCustom.news.noteRelated.INDEX = {'url':appCustom.REST_URL + 'newsNoteRelated', 'verb':'GET'};
appCustom.news.noteRelated.STORE = {'url':appCustom.REST_URL + 'newsNoteRelated', 'verb':'POST'};
appCustom.news.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.news.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteRelated/' + id;
    },
    'verb': 'PUT'
};

appCustom.news.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'newsNoteRelated/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.news.note = {};
appCustom.news.note.INDEX = {'url':appCustom.REST_URL + 'note', 'verb':'GET'};

