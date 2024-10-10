<?php

require('./pdo.php');
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    try {
        $sth = $dbh->prepare("delete from internautes where id = ?");
        $sth->execute([htmlspecialchars($_POST['id'])]);
        $result = $sth->fetch();
        var_dump($result);
    } catch (\Throwable $th) {
        throw $th;
    }

    if ($result != null && $result != false) {
        try {
            $sth = $dbh->prepare("update internautes set firstname = ?, lastname = ?, mail = ?, register_date = ? where id = ?");
            $sth->execute([
                htmlspecialchars($_POST['firstname']),
                htmlspecialchars($_POST['lastname']),
                htmlspecialchars($_POST['mail']),
                htmlspecialchars($_POST['register']),
                htmlspecialchars($result['id']),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    } else {
        try {
            $sth = $dbh->prepare("insert into internautes (firstname, lastname, mail, register_date) values (?,?,?,?)");
            $sth->execute([
                htmlspecialchars($_POST['firstname']),
                htmlspecialchars($_POST['lastname']),
                htmlspecialchars($_POST['mail']),
                htmlspecialchars($_POST['register']),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
