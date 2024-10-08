<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .customer-form {
            display: flex;
            width: 100%;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 0 0 5rem 0;
            position: relative;
        }

        body {
            padding: 5rem 10vw 0rem 10vw;
        }

        .field>label,
        .field>input {
            width: 100%;
        }

        h1 {
            padding: 0rem 0 1.5rem 0;
        }

        .label-form {
            padding: 0.5rem;
        }

        input {
            border-radius: 0.5rem;
            padding: 0.375rem 0.5rem;
            border: 1px solid #D8D8D8;
        }

        .btn-submit {
            margin-top: 0.5rem;
            position: absolute;
            bottom: 0;
            right: 0;
            cursor: pointer;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <a href="/admin.php">Admin login</a>
    <?php
    session_start();
    // echo 'hello world !';
    // $fields = [
    //     array("name" => "Prénom", "column_name" => "firstname-", "type" => "text", "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
    //     array("name" => "Nom de famille", "column_name" => "lastname-", "type" => "text", "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""), 
    //     array("name" => "Adresse", "column_name" => "adress-", "type" => "text", "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
    //     array("name" => "E-mail", "column_name" => "mail-", "type" => "email", "pattern" => "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
    //     array("name" => "Date de naissance", "column_name" => "date-", "type" => "date", "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
    //     array("name" => "Photo de profil", "column_name" => "profil-", "type" => "file", "pattern" => "","min_length" => "","max_length" => "", "size" => "","placeholder" => "")
    // ];
    
    try {
        $dsn = 'mysql:dbname=customer;host=127.0.0.1';
        $user = 'damien';
        $password = 'PETITnuage-26';

        $dbh = new PDO($dsn, $user, $password);
    } catch (\Throwable $th) {
        throw $th;
    }

    $sth = $dbh->prepare('SELECT * FROM form');
    $sth->execute();
    $fields = $sth->fetchAll();

    $message = '';
    $message_type = 'success';



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $db_fields = '';
        $db_values = [];
        $empty_fields = 0;
        $interr = '';
        $error = false;
        foreach ($fields as $key => $field) {
            if (empty($_POST[$field['column_name']]) && $field['type'] != 'file') {
                $empty_fields++;
                $error = true;
            } else {
                if ($field['type'] == 'file') {
                    // do file upload
                    if (isset($_FILES[$field['column_name']]) && $_FILES[$field['column_name']]['error'] === 0) {

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstname-']) && isset($_POST['lastname-'])) {

                            // var_dump($_FILES);
    

                            $uploadDir = 'profil/';  // Dossier où stocker les images
                            $fileName = basename($_FILES[$field['column_name']]['name']);

                            $fileType = strtolower(pathinfo($uploadDir . $fileName, PATHINFO_EXTENSION));
                            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                            $targetFilePath = $uploadDir . $_POST['firstname-'] . '.' . $_POST['lastname-'] . "_" . $field['column_name'] . '.' . $fileType;

                            if (in_array($fileType, $allowedTypes)) {
                                if (move_uploaded_file($_FILES[$field['column_name']]['tmp_name'], $targetFilePath)) {
                                    $message_type = 'success';
                                    $message = "L'image a bien été téléchargée.";
                                } else {
                                    $message_type = 'error';
                                    $message = "Une erreur s'est produite au moment du chargement de l'image.";
                                }
                            } else {
                                $message_type = 'error';
                                $message = "Seul les fichiers JPG, JPEG, PNG, et GIF sont acceptés.";
                            }
                        }
                    }
                } else {
                    if (substr($field['column_name'], strlen($field['column_name']) - 1, strlen($field['column_name']) - 1) == '-') {
                        $db_fields .= substr($field['column_name'], 0, strlen($field['column_name']) - 1) . ',';
                    } else {
                        $db_fields .= $field['column_name'] . ',';
                    }
                    $interr .= '?' . ',';
                    array_push($db_values, htmlspecialchars($_POST[$field['column_name']]));
                }
            }
        }

        if ($error != true && (strlen($db_fields) > 0 && count($db_values) > 0)) {
            // do query
            try {
                $sql = "INSERT INTO internautes (" . substr($db_fields, 0, strlen($db_fields) - 1). ",registered_date" . ") VALUES (" . substr($interr, 0, strlen($interr) - 1) . time() . ")";
                $stmt = $dbh->prepare($sql);
                $stmt->execute($db_values);
                $message_type = 'success';
                $message = "Votre formulaire a bien été soumis.";
            } catch (\Throwable $th) {
                $message_type = 'error';
                $message = "L'email ou bien le numéro de téléphone existe déjà.";
            }
        } else {
            $message_type = 'error';
            $message = strval($empty_fields) . " champs vides.";
        }
        unset($_POST);

    }
    ?>
    <h1>Customer form</h1>
    <form action="/index.php" id="customer-form" method="post" class="customer-form" enctype="multipart/form-data">
        <?php
        foreach ($fields as $key => $field):
            ?>
            <div class="label-form">
                <?php
                if (substr($field['column_name'], strlen($field['column_name']) - 1, strlen($field['column_name']) - 1) == '-'):
                    ?>
                    <?php
                    if (empty($field['pattern'])):
                        ?>

                        <?php if ($field['type'] == 'file'): ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>"
                                    maxlength="<?= $field["max_length"] ?>" class="files" accept="image/*"
                                    placeholder="<?= $field["placeholder"] ?>" required>
                            </div>
                        <?php else:
                            ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>"
                                    maxlength="<?= $field["max_length"] ?>" placeholder="<?= $field["placeholder"] ?>" required>
                            </div>
                            <?php
                        endif;
                        ?>
                    <?php else:
                        ?>
                        <?php if ($field['type'] == 'file'): ?>

                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>"
                                    minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>"
                                    placeholder="<?= $field["placeholder"] ?>" class="files" accept="image/*" required>
                            </div>
                        <?php else: ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>"
                                    minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>"
                                    placeholder="<?= $field["placeholder"] ?>" required>
                            </div>
                            <?php
                        endif;
                        ?>
                        <?php
                    endif;
                    ?>
                <?php else: ?>
                    <?php
                    if (empty($field['pattern'])):
                        ?>
                        <?php if ($field['type'] == 'file'): ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>"
                                    maxlength="<?= $field["max_length"] ?>" class="files" accept="image/*"
                                    placeholder="<?= $field["placeholder"] ?>">
                            </div>
                        <?php else:
                            ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>"
                                    maxlength="<?= $field["max_length"] ?>" placeholder="<?= $field["placeholder"] ?>">
                            </div>
                            <?php
                        endif;
                        ?>
                    <?php else:
                        ?>
                        <?php if ($field['type'] == 'file'): ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>"
                                    minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>"
                                    placeholder="<?= $field["placeholder"] ?>" class="files" accept="image/*">
                            </div>
                        <?php else:
                            ?>
                            <div class="field">
                                <label for="<?= $field["column_name"] ?>"><?= $field["name"] ?></label>
                                <input id="<?= $field["column_name"] ?>" name="<?= $field["column_name"] ?>"
                                    type="<?= $field["type"] ?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>"
                                    minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>"
                                    placeholder="<?= $field["placeholder"] ?>">
                            </div>
                            <?php
                        endif;
                        ?>
                        <?php
                    endif;
                    ?>
                    <?php
                endif;
                ?>
            </div>
            <?php
        endforeach;
        ?>
        <input type="submit" class="btn-submit create" value="Créer mon compte" />
    </form>

    <!-- Afficher un aperçu de l'image uploadée -->
    <img id="preview" style="display:none;" alt="Image Preview" style="max-width: 200px;">

    <?php if (
        $message_type == 'error'
    ): ?>
        <div class="error">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <?php if (
        $message_type == 'success'
    ): ?>
        <div class="success">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            $("#customer-form").on("submit", function(event) {
                event.preventDefault(); // Empêche le rechargement de la page

                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#response").html(response); // Affiche la réponse du serveur
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
                        document.getElementsByClassName('error')[0].innerText = ""
                        console.log('it fits')
                    } else {
                        // it doesn't fit, unset the value 
                        // post an error
                        fileInput.value = ""
                        document.getElementsByClassName('error')[0].innerText = "Veuillez renseigner une image avec les dimensions correspondantes : 200px x 200px."
                        // alert("only 200x200 images")
                    }
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
                        console.log('it fits')
                    } else {
                        e.stopPropagation()
                        document.getElementsByClassName('error')[0].innerText = "Veuillez renseigner une image avec les dimensions correspondantes : 200px x 200px."
                        // alert("only 200x200 images")
                    }
                };

            };

        }


    </script>
</body>

</html>