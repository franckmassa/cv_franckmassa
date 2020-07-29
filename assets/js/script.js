// Réglage de la vitesse du carousel
$('.carousel').carousel({
    interval: 1800
});

// Animation lorsque l'on clique sur les liens du menu ou du footer 
$(function () {
    $('.navbar a, footer a').on('click', function (event) {
        event.preventDefault();
        var hash = this.hash;
// Ne pas oublier de mettre l'élément html pour le fonctionnement avec firefox
        $('body,html').animate({
            scrollTop: $(hash).offset().top}
        , 900 , function() {
            window.location.hash = hash;
        });
    });

    // Formulaire de contact 
    $('#contact-form').submit(function(e) {
        
        // Comportement par défaut
        e.preventDefault();
        // On vide les messages d'erreurs
        $('.comments').empty();
        // Stockage de toutes les infos du formulaire dans une variable, sous forme d'une chaine de caractères
        var postData = $('#contact-form').serialize();


        $.ajax({
            type: 'POST',
            // On envoi vers l'url
            url: 'php/contact.php',
            // On envoi la variable
            data: postData,
            dataType: 'json',
            // Si tout c'est bien passé, on exécute la fonction
            success: function (result) {

                if (result.isSuccess) {
                    $("#contact-form").append("<p class='thank-you'>Votre message a bien été envoyé. Merci de m'avoir contacté :)</p>");
                    $("#contact-form")[0].reset();
                }
                else 
                {
                    $("#firstname + .comments").html(result.firstnameError);
                    $("#name + .comments").html(result.nameError);
                    $("#email + .comments").html(result.emailError);
                    $("#phone + .comments").html(result.phoneError);
                    $("#message + .comments").html(result.messageError);
                }
            }
        });
    });
})

