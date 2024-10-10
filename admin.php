<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
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
            margin: 1rem 0;
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

        /* handsontable style */
        /*
        A stylesheet customizing app (custom renderers)
        */
        table.htCore tr.odd td {
            background: #fafbff;
        }

        /*
        A stylesheet customizing Handsontable style
        */

        .collapsibleIndicator {
            text-align: center;
        }

        .handsontable .htRight .changeType {
            margin: 3px 1px 0 13px;
        }

        .handsontable .green {
            background: #37bc6c;
            font-weight: bold;
        }

        .handsontable .orange {
            background: #fcb515;
            font-weight: bold;
        }

        .btn {
            padding: 20px;
            font: 1.4em sans-serif;
        }

        [data-color="green"] {
            background: #37bc6c;
        }

        [data-color="orange"] {
            background: #fcb515;
        }
    </style>
</head>

<body>
    <?php

    require('./pdo.php');

    // var_dump($_SESSION);
    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idToRemove'])) {
    //     var_dump($_POST);
    // }

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == "true") {

        try {
            // echo "hello world";
            $sth = $dbh->prepare('SELECT * FROM internautes');
            $sth->execute();
            $internautes = $sth->fetchAll();
            $data = [];
            for ($i = 0; $i < count($internautes); $i++) {
                $id = $internautes[$i]['id'];
                $mail = $internautes[$i]['mail'];
                $lastname = $internautes[$i]['lastname'];
                $fistname = substr($internautes[$i]['firstname'], 0, 1);
                $register_date = date("j-m-y", $internautes[$i]['registered_date']);
                array_push($data, array(
                    $id,
                    $mail,
                    $lastname,
                    $firstname,
                    $register_date
                ));
            }
            $_SESSION['data'] = json_encode($data);
            // var_dump($data);
        } catch (\Throwable $th) {
            throw $th;
        }


        // sleep(3000);
        // header("Refresh:0; url=admin.php");
        $message = 'Vous êtes connecté';
        $message_type = 'success';
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $sth = $dbh->prepare('SELECT * FROM admin where `email` = ?');
                // echo 'SELECT * FROM admin where `email` = ? & `password` = ?';
                echo $password_check;
                $sth->execute([htmlspecialchars($_POST['email'])]);
                // echo htmlspecialchars($_POST['email']);
                // echo htmlspecialchars($_POST['password']);
                $admin = $sth->fetch();
                $password_check = password_verify(htmlspecialchars($_POST['password']), $admin['password']);
                // var_dump($admin);
                // echo count($admin) > 0;
                if ($password_check) {
                    $_SESSION['logged_in'] = "true";
                    // echo "in";
                    // echo $SESSION;
                    try {
                        // echo "hello world";
                        $sth = $dbh->prepare('SELECT * FROM internautes');
                        $sth->execute();
                        $internautes = $sth->fetchAll();
                        $data = [];
                        for ($i = 0; $i < count($internautes); $i++) {
                            $id = $internautes[$i]['id'];
                            $mail = $internautes[$i]['mail'];
                            $lastname = $internautes[$i]['lastname'];
                            $firstname = substr(trim($internautes[$i]['firstname']), 0, 1);
                            $register_date = date("d-m-y", $internautes[$i]['registered_date']);
                            array_push($data, array(
                                $id,
                                $mail,
                                $lastname,
                                $firstname,
                                $register_date
                            ));
                        }
                        $_SESSION['data'] = json_encode($data);
                        // var_dump($data);
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                }
                // var_dump($admin);
            } catch (\Throwable $th) {
                throw $th;
            }
            $message = 'Vous êtes bien connecté.';
            $message_type = 'success';
        }
    }
    ?>
    <p><a href="index.php">Page d'accueil</a></p>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == "true"): ?>
        <p><a href="logout.php">Logout</a></p>
        <div id="handsontable"></div>
    <?php else: ?>
        <h1>Admin login</h1>
        <form action="admin.php" method="post">
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                    placeholder="Saisir un email..." required>

                <label for="password">Password</label>
                <input id="password" name="password" type="password" class="password"
                    pattern="/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/" placeholder="Saisir un mot de passe..." required>
            </div>
            <input type="submit" class="btn-login" value="Me connecter" />
        </form>
    <?php endif; ?>
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
        // import Handsontable from "handsontable";
        // import "handsontable/dist/handsontable.min.css";

        const example = document.getElementById("handsontable");
        var previousRemovedData = [];
        console.log("<?php echo $_SESSION['email']; ?>")
        console.log("<?php echo $_SESSION['logged_in']; ?>")
        const headerAlignments = new Map([
            ["9", "htCenter"],
            ["10", "htRight"],
            ["12", "htCenter"]
        ]);

        // function setPreviousRemovedData(data) {
        //     previousRemovedData = data;
        // }

        // function getPreviousRemovedData(data) {
        //     return previousRemovedData;
        // }

        function addClassesToRows(TD, row, column, prop, value, cellProperties) {
            // Adding classes to `TR` just while rendering first visible `TD` element
            if (column !== 0) {
                return;
            }

            const parentElement = TD.parentElement;

            if (parentElement === null) {
                return;
            }

            // Add class to odd TRs
            if (row % 2 === 0) {
                Handsontable.dom.addClass(parentElement, data);
            } else {
                Handsontable.dom.removeClass(parentElement, data);
            }
        }

        function drawCheckboxInRowHeaders(row, TH) {
            const input = document.createElement("input");

            input.type = "checkbox";

            if (row >= 0 && this.getDataAtRowProp(row, "0")) {
                input.checked = true;
            }

            Handsontable.dom.empty(TH);

            TH.appendChild(input);
        }

        function alignHeaders(column, TH) {
            if (column < 0) {
                return;
            }

            if (TH.firstChild) {
                const alignmentClass = this.isRtl() ? "htRight" : "htLeft";

                if (headerAlignments.has(column.toString())) {
                    Handsontable.dom.removeClass(TH.firstChild, alignmentClass);
                    Handsontable.dom.addClass(
                        TH.firstChild,
                        headerAlignments.get(column.toString())
                    );
                } else {
                    Handsontable.dom.addClass(TH.firstChild, alignmentClass);
                }
            }
        }

        function changeCheckboxCell(event, coords) {
            const target = event.target;

            if (coords.col === -1 && target && target.nodeName === "INPUT") {
                event.preventDefault(); // Handsontable will render checked/unchecked checkbox by it own.

                this.setDataAtRowProp(coords.row, "0", !target.checked);
            }
        }

        function removeRow(id) {

            const formData = new FormData();

            formData.append("idToRemove", id);

            const data = new URLSearchParams(formData);

            let url = 'delete.php'

            const response = fetch(url, {
                method: "POST", // *GET, POST, PUT, DELETE, etc.
                // mode: "cors", // no-cors, *cors, same-origin
                // cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                // credentials: "same-origin", // include, *same-origin, omit
                headers: {
                // "Content-Type": "application/json",
                'Content-Type': 'application/x-www-form-urlencoded',
                },
                // redirect: "follow", // manual, *follow, error
                // referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: data, // le type utilisé pour le corps doit correspondre à l'en-tête "Content-Type"
            });
            // console.log(response.json())
            
        }

        if ('<?php echo $_SESSION['data']; ?>' != undefined && '<?php echo strlen($_SESSION['data']); ?>' > 0) {
            data = JSON.parse('<?php echo $_SESSION['data']; ?>')
            console.log(data);

            const hot = new Handsontable(example, {
                data,
                height: 450,
                colWidths: [170, 156, 222, 130, 130, 120, 120],
                colHeaders: [
                    "ID",
                    "E-mail adress",
                    "Lastname",
                    "Firstname",
                    "Register date"
                ],
                columns: [{
                        data: 0,
                        type: "numeric"
                    },
                    {
                        data: 1,
                        type: "text"
                    },
                    {
                        data: 2,
                        type: "text"
                    },
                    {
                        data: 3,
                        type: "text"
                    },
                    {
                        data: 4,
                        type: "date",
                        dateFormat: 'DD-MM-YY',
                        // allowInvalid: false,
                    },
                ],
                dropdownMenu: true,
                hiddenColumns: {
                    indicators: true,
                },
                contextMenu: true,
                multiColumnSorting: true,
                filters: true,
                rowHeaders: true,
                manualRowMove: true,
                autoWrapCol: true,
                autoWrapRow: true,
                beforeRemoveRow: function(row, col) {
                    console.log(row)
                    var m = hot.getDataAtCell(row, 0);
                    removeRow(m)
                },
                afterSelection: function (r, c) {
                    var da = this.getDataAtRow(r);
                    selectedRow = "";
                    selectedRow = da[0];
                    console.log(selectedRow);

                },
                afterGetColHeader: alignHeaders,
                beforeRenderer: addClassesToRows,
                licenseKey: "non-commercial-and-evaluation",
            });
        }
    </script>
</body>

</html>