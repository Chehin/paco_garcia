//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingEstadisticas = {};
appCustom.maillingEstadisticas.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticas', 'verb': 'GET' };
appCustom.maillingEstadisticas.CREATE = { 'url': appCustom.REST_URL + 'maillingEstadisticas/create', 'verb': 'GET' };
appCustom.maillingEstadisticas.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticas', 'verb': 'POST' };
appCustom.maillingEstadisticas.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticas/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingEstadisticas.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticas/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingEstadisticas.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticas/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingEstadisticas.image = {};

appCustom.maillingEstadisticas.image.mainView = {
    'url': function (id) {
        return 'maillingEstadisticas/imageMain/' + id;
    }
};

appCustom.maillingEstadisticas.image.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasImage', 'verb': 'GET' };
appCustom.maillingEstadisticas.image.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasImage', 'verb': 'POST' };
appCustom.maillingEstadisticas.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingEstadisticas.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingEstadisticas.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasImage/' + id;
    },
    'verb': 'DELETE'
};