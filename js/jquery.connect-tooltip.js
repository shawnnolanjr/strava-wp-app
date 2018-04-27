(function ($) {
    $('li#accordion-section-wsc_api_section').on('click', function(){
        console.log('clicked');
        console.log($('body').find('.customize-control-title').html());
    });
    $('body').find('#customize-control-wsc_api_user_details label span').attr('title', 'My tooltip!');
    $('body').find('.customize-control-title').tooltip();
}(jQuery));