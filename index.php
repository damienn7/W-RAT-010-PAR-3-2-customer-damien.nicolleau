<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer form</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .customer-form {
            display: flex;
            width 100%;
            justify-content: space-between;
            flex-wrap: wrap;
            padding:  0 0 5rem 0;
            position: relative;
        }

        body {
            padding: 5rem 10vw 0rem 10vw;
        }

        .field > label, .field > input {
            width: 100%;
        }

        h1 {
            padding: 0rem 0 1.5rem 0 ;
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
    </style>
</head>
<body>
    <?php 
            // echo 'hello world !';
        $fields = [
            array("name" => "Prénom", "column_name" => "firstname", "type" => "text", "is_mandatory" => true, "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
            array("name" => "Nom de famille", "column_name" => "lastname", "type" => "text", "is_mandatory" => true, "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""), 
            array("name" => "Adresse", "column_name" => "adress", "type" => "text", "is_mandatory" => true, "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
            array("name" => "E-mail", "column_name" => "mail", "type" => "email", "is_mandatory" => true, "pattern" => "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
            array("name" => "Date de naissance", "column_name" => "date", "type" => "date", "is_mandatory" => true, "pattern" => "", "min_length" => "","max_length" => "", "size" => "","placeholder" => ""),
            array("name" => "Photo de profil", "column_name" => "image_url", "type" => "file", "is_mandatory" => true, "pattern" => "","min_length" => "","max_length" => "", "size" => "","placeholder" => "")
        ];
    ?>
    <h1>Customer form</h1>
    <form action="" method="post" class="customer-form">
        <?php 
            foreach($fields as $key => $field):
        ?>
        <div class="label-form">
            <?php 
                if($field['is_mandatory']):
            ?>
            <div class="field">
                <label for="<?= $field["column_name"]?>"><?= $field["name"]?></label>
                <input id="<?= $field["column_name"]?>" type="<?= $field["type"]?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>" placeholder="<?= $field["placeholder"] ?>" required>
            </div>
            <?php 
                else:
            ?>  
                <div class="field">
                    <label for="<?= $field["column_name"]?>"><?= $field["name"]?></label>
                    <input type="<?= $field["type"]?>" pattern="<?= $field["pattern"] ?>" size="<?= $field["size"] ?>" minlength="<?= $field["min_length"] ?>" maxlength="<?= $field["max_length"] ?>" placeholder="<?= $field["placeholder"] ?>" >
                </div>
            <?php 
                endif;
            ?>
        </div>
        <?php
            endforeach;
        ?>
        <input type="submit" class="btn-submit create" value="Créer mon compte" />
    </form>
</body>
</html>