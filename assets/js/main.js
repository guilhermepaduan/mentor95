$( document ).ready(function() {
    $('#form-next-btn').on( "click", function() {
        $('.form-step').removeClass( "active" );
        $('.user-infos').addClass( "active" );
    });

    $('#form-prev-btn').on( "click", function() {
        $('.form-step').removeClass( "active" );
        $('.tag-cloud').addClass( "active" );
    });
});