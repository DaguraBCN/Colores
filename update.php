<?php
session_start(); // Iniciar sesión para manejar mensajes de error
include_once 'connection.php';

$id = $_GET['id'];
$usuario = trim($_GET['usuario']);
$color = trim($_GET['color']);
$coloresValidos = ['red', 'green', 'blue', 'yellow', 'pink', 'white']; // Lista de colores válidos

if (empty($usuario) || empty($color)) {
    $_SESSION['error'] = 'Los campos no pueden estar vacíos.';
    header('Location: index.php');
    exit;
}

if (!in_array(strtolower($color), $coloresValidos)) {
    $_SESSION['error'] = 'No existe el color indicado.';
    header('Location: index.php');
    exit;
}

if (!$_GET["reset"]){
    $queryUpdate = "UPDATE colores SET usuario = ?, color = ? WHERE id_colores = ?";
    $sqlUpdate = $conn->prepare($queryUpdate);
    $sqlUpdate->execute([$usuario, $color, $id]);
}

$sqlUpdate = null;
$conn = null;

header('Location: index.php');
?>
