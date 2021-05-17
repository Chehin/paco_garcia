//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingTemplates = {};
appCustom.maillingTemplates.INDEX = { 'url': appCustom.REST_URL + 'maillingTemplates', 'verb': 'GET' };
appCustom.maillingTemplates.CREATE = { 'url': appCustom.REST_URL + 'maillingTemplates/create', 'verb': 'GET' };
appCustom.maillingTemplates.STORE = { 'url': appCustom.REST_URL + 'maillingTemplates', 'verb': 'POST' };
appCustom.maillingTemplates.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplates/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingTemplates.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplates/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingTemplates.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplates/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingTemplates.image = {};

appCustom.maillingTemplates.image.mainView = {
    'url': function (id) {
        return 'maillingTemplates/imageMain/' + id;
    }
};

appCustom.maillingTemplates.image.INDEX = { 'url': appCustom.REST_URL + 'maillingTemplatesImage', 'verb': 'GET' };
appCustom.maillingTemplates.image.STORE = { 'url': appCustom.REST_URL + 'maillingTemplatesImage', 'verb': 'POST' };
appCustom.maillingTemplates.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplatesImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingTemplates.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplatesImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingTemplates.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTemplatesImage/' + id;
    },
    'verb': 'DELETE'
};