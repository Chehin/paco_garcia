//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingCampanias = {};
appCustom.maillingCampanias.INDEX = { 'url': appCustom.REST_URL + 'maillingCampanias', 'verb': 'GET' };
appCustom.maillingCampanias.CREATE = { 'url': appCustom.REST_URL + 'maillingCampanias/create', 'verb': 'GET' };
appCustom.maillingCampanias.STORE = { 'url': appCustom.REST_URL + 'maillingCampanias', 'verb': 'POST' };
appCustom.maillingCampanias.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampanias/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingCampanias.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampanias/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingCampanias.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampanias/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingCampanias.image = {};

appCustom.maillingCampanias.image.mainView = {
    'url': function (id) {
        return 'maillingCampanias/imageMain/' + id;
    }
};

appCustom.maillingCampanias.image.INDEX = { 'url': appCustom.REST_URL + 'maillingCampaniasImage', 'verb': 'GET' };
appCustom.maillingCampanias.image.STORE = { 'url': appCustom.REST_URL + 'maillingCampaniasImage', 'verb': 'POST' };
appCustom.maillingCampanias.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampaniasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingCampanias.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampaniasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingCampanias.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingCampaniasImage/' + id;
    },
    'verb': 'DELETE'
};