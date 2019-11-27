$( document ).ready(function() {
  function h1_text () {
    if ($('.user-infos').hasClass('active')) {
      $('.signin-section h1').empty().text('Finalize sua inscrição:')
    }

    else if ($('.form-step').hasClass('active')) {
      $('.signin-section h1').empty().text('Escolha uma opção para se cadastrar:')
    }
  }

  $('.tag-cloud input').on('change', function() {
    $('#form-next-btn').removeClass('disabled');
  });

  $('#form-next-btn').on( "click", function() {
    $('.form-step').removeClass( "active" );
    $('.user-infos').addClass( "active" );

    h1_text()
  });

  $('#form-prev-btn').on( "click", function() {
    $('.form-step').removeClass( "active" );
    $('.tag-cloud').addClass( "active" );

    h1_text()
  });

  //Validação simples
  $("#formRegister").validate();
});
