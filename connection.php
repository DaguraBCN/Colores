<?php

$host = "localhost";
$port = "3306";
$database = "colores";

$user = "root";
$password = "";

$link = "mysql:host=$host;port=$port;dbname=$database";

try {

    $conn = new PDO($link, $user, $password);

    // echo "Conectado!!";

} catch(PDOException $e) {

    // mensaje por si parece un error
    print "¡Error!". $e->getMessage()."<br>";

}


?>
