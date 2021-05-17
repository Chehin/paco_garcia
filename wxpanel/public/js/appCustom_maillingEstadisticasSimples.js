//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingEstadisticasSimples = {};
appCustom.maillingEstadisticasSimples.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasSimples', 'verb': 'GET' };
appCustom.maillingEstadisticasSimples.CREATE = { 'url': appCustom.REST_URL + 'maillingEstadisticasSimples/create', 'verb': 'GET' };
appCustom.maillingEstadisticasSimples.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasSimples', 'verb': 'POST' };
appCustom.maillingEstadisticasSimples.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimples/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingEstadisticasSimples.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimples/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingEstadisticasSimples.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimples/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingEstadisticasSimples.image = {};

appCustom.maillingEstadisticasSimples.image.mainView = {
    'url': function (id) {
        return 'maillingEstadisticasSimples/imageMain/' + id;
    }
};

appCustom.maillingEstadisticasSimples.image.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasSimplesImage', 'verb': 'GET' };
appCustom.maillingEstadisticasSimples.image.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasSimplesImage', 'verb': 'POST' };
appCustom.maillingEstadisticasSimples.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimplesImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingEstadisticasSimples.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimplesImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingEstadisticasSimples.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasSimplesImage/' + id;
    },
    'verb': 'DELETE'
};