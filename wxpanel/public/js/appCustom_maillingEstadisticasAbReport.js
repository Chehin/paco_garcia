//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingEstadisticasAbReport = {};
appCustom.maillingEstadisticasAbReport.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasAbReport', 'verb': 'GET' };
appCustom.maillingEstadisticasAbReport.CREATE = { 'url': appCustom.REST_URL + 'maillingEstadisticasAbReport/create', 'verb': 'GET' };
appCustom.maillingEstadisticasAbReport.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasAbReport', 'verb': 'POST' };
appCustom.maillingEstadisticasAbReport.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReport/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingEstadisticasAbReport.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReport/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingEstadisticasAbReport.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReport/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingEstadisticasAbReport.image = {};

appCustom.maillingEstadisticasAbReport.image.mainView = {
    'url': function (id) {
        return 'maillingEstadisticasAbReport/imageMain/' + id;
    }
};

appCustom.maillingEstadisticasAbReport.image.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasAbReportImage', 'verb': 'GET' };
appCustom.maillingEstadisticasAbReport.image.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasAbReportImage', 'verb': 'POST' };
appCustom.maillingEstadisticasAbReport.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReportImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingEstadisticasAbReport.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReportImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingEstadisticasAbReport.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasAbReportImage/' + id;
    },
    'verb': 'DELETE'
};