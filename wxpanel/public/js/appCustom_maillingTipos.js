//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingTipos = {};
appCustom.maillingTipos.INDEX = { 'url': appCustom.REST_URL + 'maillingTipos', 'verb': 'GET' };
appCustom.maillingTipos.CREATE = { 'url': appCustom.REST_URL + 'maillingTipos/create', 'verb': 'GET' };
appCustom.maillingTipos.STORE = { 'url': appCustom.REST_URL + 'maillingTipos', 'verb': 'POST' };
appCustom.maillingTipos.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTipos/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingTipos.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTipos/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingTipos.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTipos/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingTipos.image = {};

appCustom.maillingTipos.image.mainView = {
    'url': function (id) {
        return 'maillingTipos/imageMain/' + id;
    }
};

appCustom.maillingTipos.image.INDEX = { 'url': appCustom.REST_URL + 'maillingTiposImage', 'verb': 'GET' };
appCustom.maillingTipos.image.STORE = { 'url': appCustom.REST_URL + 'maillingTiposImage', 'verb': 'POST' };
appCustom.maillingTipos.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTiposImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingTipos.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTiposImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingTipos.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingTiposImage/' + id;
    },
    'verb': 'DELETE'
};