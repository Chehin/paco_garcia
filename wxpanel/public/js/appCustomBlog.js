//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.blog = {};
appCustom.blog.INDEX = {'url':appCustom.REST_URL + 'blog', 'verb':'GET'};
appCustom.blog.CREATE = {'url':appCustom.REST_URL + 'blog/create', 'verb':'GET'};
appCustom.blog.STORE = {'url':appCustom.REST_URL + 'blog', 'verb':'POST'};
appCustom.blog.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blog/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.blog.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blog/' + id;
    },
    'verb': 'PUT'
};
appCustom.blog.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blog/' + id;
    },
    'verb': 'DELETE'
};
//image
appCustom.blog.image = {};

appCustom.blog.image.mainView = {
    'url':function(id){
        return 'blog/imageMain/' + id;
    }
};

appCustom.blog.image.INDEX = {'url':appCustom.REST_URL + 'blogImage', 'verb':'GET'};
appCustom.blog.image.STORE = {'url':appCustom.REST_URL + 'blogImage', 'verb':'POST'};
appCustom.blog.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blogImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.blog.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blogImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.blog.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blogImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.blog.noteRelated = {};

appCustom.blog.noteRelated.mainView = {
    'url':function(id){
        return 'blog/noteRelatedMain/' + id;
    }
};

appCustom.blog.noteRelated.INDEX = {'url':appCustom.REST_URL + 'itemRelationRelated', 'verb':'GET'};
appCustom.blog.noteRelated.STORE = {'url':appCustom.REST_URL + 'itemRelation', 'verb':'POST'};
appCustom.blog.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'blogNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.blog.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'itemRelation/' + id;
    },
    'verb': 'PUT'
};

appCustom.blog.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'itemRelation/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.blog.note = {};
appCustom.blog.note.INDEX = {'url':appCustom.REST_URL + 'itemRelation', 'verb':'GET'};

