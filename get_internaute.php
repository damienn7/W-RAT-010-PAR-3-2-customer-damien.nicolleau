<?php
require('./pdo.php');
// var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    try {
        $sth = $dbh->prepare("select * from internautes where id = ?");
        $sth->execute([htmlspecialchars($_POST['id'])]);
        $result = $sth->fetch();
        // var_dump($result);
        if ($result != null && $result != false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}
