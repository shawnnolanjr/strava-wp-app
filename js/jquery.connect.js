(function ( $ ) {

    $.fn.stravaConnect = function() {
        var options = $.extend({},$.Defaults, options);
        console.log(options);
    }

    $.Defaults = {
        'firstName': 'shawn'
    };


    $.fn.stravaConnect();
}( jQuery ));