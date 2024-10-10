<?php
try {
    $dsn = 'mysql:dbname=customer;host=127.0.0.1';
    $user = 'damien';
    $password = 'PETITnuage-26';
    $dbh = new PDO($dsn, $user, $password);
} catch (\Throwable $th) {
    throw $th;
}

?>