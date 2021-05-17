//appCustom.js required!

//REST REQUESTs CONFIG
appCustom.user = {};
appCustom.user.INDEX = {'url':appCustom.REST_URL + 'user', 'verb':'GET'};
appCustom.user.CREATE = {'url':appCustom.REST_URL + 'user/create', 'verb':'GET'};
appCustom.user.STORE = {'url':appCustom.REST_URL + 'user', 'verb':'POST'};
appCustom.user.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'user/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.user.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'user/' + id;
    },
    'verb': 'PUT'
};
appCustom.user.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'user/' + id;
    },
    'verb': 'DELETE'
};

//role
appCustom.role = {};
appCustom.role.INDEX = {'url':appCustom.REST_URL + 'role', 'verb':'GET'};
appCustom.role.CREATE = {'url':appCustom.REST_URL + 'role/create', 'verb':'GET'};
appCustom.role.STORE = {'url':appCustom.REST_URL + 'role', 'verb':'POST'};
appCustom.role.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'role/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.role.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'role/' + id;
    },
    'verb': 'PUT'
};
appCustom.role.DELETE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'role/' + id;
    },
    'verb': 'DELETE'
};

//permissions
appCustom.permission = {};
appCustom.permission.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'permission/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.permission.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'permission/' + id;
    },
    'verb': 'PUT'
};
//Role assign
appCustom.roleAssign = {};
appCustom.roleAssign.EDIT = {
    'url':function(id) { 
        return appCustom.REST_URL + 'roleAssign/' + id + '/edit';
    },
    'verb': 'GET'
};
appCustom.roleAssign.UPDATE = {
    'url':function(id) { 
        return appCustom.REST_URL + 'roleAssign/' + id;
    },
    'verb': 'PUT'
};
