//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.maillingEstadisticasReport = {};
appCustom.maillingEstadisticasReport.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasReport', 'verb': 'GET' };
appCustom.maillingEstadisticasReport.CREATE = { 'url': appCustom.REST_URL + 'maillingEstadisticasReport/create', 'verb': 'GET' };
appCustom.maillingEstadisticasReport.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasReport', 'verb': 'POST' };
appCustom.maillingEstadisticasReport.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReport/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.maillingEstadisticasReport.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReport/' + id;
    },
    'verb': 'PUT'
};
appCustom.maillingEstadisticasReport.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReport/' + id;
    },
    'verb': 'DELETE'
};

//image
appCustom.maillingEstadisticasReport.image = {};

appCustom.maillingEstadisticasReport.image.mainView = {
    'url': function (id) {
        return 'maillingEstadisticasReport/imageMain/' + id;
    }
};

appCustom.maillingEstadisticasReport.image.INDEX = { 'url': appCustom.REST_URL + 'maillingEstadisticasReportImage', 'verb': 'GET' };
appCustom.maillingEstadisticasReport.image.STORE = { 'url': appCustom.REST_URL + 'maillingEstadisticasReportImage', 'verb': 'POST' };
appCustom.maillingEstadisticasReport.image.EDIT = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReportImage/' + id + '/edit';
    },
    'verb': 'GET'
};

appCustom.maillingEstadisticasReport.image.UPDATE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReportImage/' + id;
    },
    'verb': 'PUT'
};

appCustom.maillingEstadisticasReport.image.DELETE = {
    'url': function (id) {
        return appCustom.REST_URL + 'maillingEstadisticasReportImage/' + id;
    },
    'verb': 'DELETE'
};