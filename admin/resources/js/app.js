require('./bootstrap');


/* Confirm delete action */
$(function() {
    $('.actionRemove').click(function () {
        if (window.confirm("Are You Sure?")) {
            return true;
        } else {
            return false;
        }
    });
});
/* Confirm delete action */
