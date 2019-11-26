$( document ).ready(function() {
    $('#form-next-btn').on( "click", function() {
        $('.form-step').removeClass( "active" );
        $('.user-infos').addClass( "active" );
    });
});