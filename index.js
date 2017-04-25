var user;
$(document).ready(function() {
    user = $('#user');
    pageIneraction();
    
    if (user.length == 1) {
        getEvents('getUserEvents');
    } else {
        getEvents('getEvents');
    }
    
});