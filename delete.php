<?php

require('./pdo.php');
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idToRemove'])) {
    
    try {
        $sth = $dbh->prepare("delete from internautes where id = ?");
        $sth->execute([htmlspecialchars($_POST['idToRemove'])]);
    } catch (\Throwable $th) {
        throw $th;
    }
}

?>