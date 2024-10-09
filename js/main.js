import { $ } from "./jQuery.min.js";


$(document).ready(function() {
    $("#customer-form").on("submit", function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        console.log('submitting request...')
        // console.log(event);
        console.log($(this).serialize())
        $.ajax({
            type: "POST",
            url: "index.php",
            data: $(this).serialize(),
            success: function(response) {
                $("#response").html(response); // Affiche la réponse du serveur
                // console.log(response)
            },
            error: function() {
                $("#response").html("Une erreur s'est produite lors de l'envoi des données.");
            }
        });
    });
});

let fileInputs = document.getElementsByClassName('files');
for (let index = 0; index < fileInputs.length; index++) {
    const fileInput = fileInputs[index];
    // console.log(fileInput)
    let form = document.getElementById('customer-form')

    fileInput.onchange = function (e) {
        // get the file someone selected
        var file = fileInput.files && fileInput.files[0];

        // create an image element with that selected file
        var img = new Image();
        img.src = window.URL.createObjectURL(file);

        // as soon as the image has been loaded
        img.onload = function () {
            var width = img.naturalWidth,
                height = img.naturalHeight;

            // check its dimensions
            if (width == 200 && height == 200) {
                // it fits
                // we do nothing here
                if (document.getElementsByClassName('error')[0] != undefined) {
                    console.log('in error js set')
                    document.getElementsByClassName('error')[0].innerText = ""
                }

                console.log('it fits')
            } else {
                // it doesn't fit, unset the value 
                // post an error
                fileInput.value = ""
                if (document.getElementsByClassName('error')[0] != undefined) {
                    document.getElementsByClassName('error')[0].innerText = "Veuillez renseigner une image avec les dimensions correspondantes : 200px x 200px."
                }
                // alert("only 200x200 images")
            }
            window.URL.removeObjectURL(file)
        };
    }

    form.onsubmit = function (e) {
        // e.preventDefault();

        // get the file someone selected
        var file = fileInput.files && fileInput.files[0];

        // create an image element with that selected file
        var img = new Image();
        img.src = window.URL.createObjectURL(file);

        // as soon as the image has been loaded
        img.onload = function () {
            var width = img.naturalWidth,
                height = img.naturalHeight;

            // check its dimensions
            if (width == 200 && height == 200) {
                // it fits
                // we do nothing here
                console.log('it fits 2')
            } else {
                e.stopPropagation()
                document.getElementsByClassName('error')[0].innerText = "Veuillez renseigner une image avec les dimensions correspondantes : 200px x 200px."
                // alert("only 200x200 images")
            }
        };

    };

}

