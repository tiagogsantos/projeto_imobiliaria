$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* quando o formulario com o name "login" for submetido */
    $('form[name="login"]').submit(function (event) {
        /* Aqui eu previno para que ele tenha um comportamento padrão */
        event.preventDefault();

        const form = $(this);
        const action = form.attr('action');
        const email = form.find('input[name="email"]').val();
        const password = form.find('input[name="password_check"]').val();


        /* Para aonde eu quero postar e quais são os parametros */
        $.post(action, {email: email, password: password}, function (response) {
         console.log(response);

         /* Existe o indice na resposta */
         if (response.message) {
             ajaxMessage(response.message, 3);
         }

         // fazendo o redirecionamento pelo php
         if (response.redirect) {
             window.location.href = response.redirect;
         }

    }, 'json');

    });

    // AJAX RESPONSE
    var ajaxResponseBaseTime = 3;

    function ajaxMessage(message, time) {
        var ajaxMessage = $(message);

        ajaxMessage.append("<div class='message_time'></div>");
        ajaxMessage.find(".message_time").animate({"width": "100%"}, time * 1000, function () {
            $(this).parents(".message").fadeOut(200);
        });

        $(".ajax_response").append(ajaxMessage);
    }

    // AJAX RESPONSE MONITOR
    $(".ajax_response .message").each(function (e, m) {
        ajaxMessage(m, ajaxResponseBaseTime += 1);
    });

    // AJAX MESSAGE CLOSE ON CLICK
    $(".ajax_response").on("click", ".message", function (e) {
        $(this).effect("bounce").fadeOut(1);
    });

});
