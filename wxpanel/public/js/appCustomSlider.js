//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.slider = {};
appCustom.slider.INDEX = {'url':appCustom.REST_URL + 'slider', 'verb':'GET'};
appCustom.slider.CREATE = {'url':appCustom.REST_URL + 'slider/create', 'verb':'GET'};
appCustom.slider.STORE = {'url':appCustom.REST_URL + 'slider', 'verb':'POST'};
appCustom.slider.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'slider/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.slider.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'slider/' + id;
    },
    'verb': 'PUT'
};
appCustom.slider.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'slider/' + id;
    },
    'verb': 'DELETE'
};
//image
appCustom.slider.image = {};

appCustom.slider.image.mainView = {
    'url':function(id){
        return 'slider/imageMain/' + id;
    }
};

appCustom.slider.image.INDEX = {'url':appCustom.REST_URL + 'sliderImage', 'verb':'GET'};
appCustom.slider.image.STORE = {'url':appCustom.REST_URL + 'sliderImage', 'verb':'POST'};
appCustom.slider.image.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sliderImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.slider.image.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sliderImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.slider.image.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sliderImage/' + id;
    },
    'verb': 'DELETE'
};

//Note related
appCustom.slider.noteRelated = {};

appCustom.slider.noteRelated.mainView = {
    'url':function(id){
        return 'slider/noteRelatedMain/' + id;
    }
};

appCustom.slider.noteRelated.INDEX = {'url':appCustom.REST_URL + 'itemRelationRelated', 'verb':'GET'};
appCustom.slider.noteRelated.STORE = {'url':appCustom.REST_URL + 'itemRelation', 'verb':'POST'};
appCustom.slider.noteRelated.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'sliderNoteRelated/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.slider.noteRelated.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'itemRelation/' + id;
    },
    'verb': 'PUT'
};

appCustom.slider.noteRelated.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'itemRelation/' + id;
    },
    'verb': 'DELETE'
};


//Notes
appCustom.slider.note = {};
appCustom.slider.note.INDEX = {'url':appCustom.REST_URL + 'itemRelation', 'verb':'GET'};

