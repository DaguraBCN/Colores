<?php
include_once 'connection.php';

$id = $_GET['id'];

$queryDelete = "DELETE FROM colores WHERE id_colores = ?";

$sqlDelete = $conn -> prepare($queryDelete);
$sqlDelete -> execute([$id]);

$sqlDelete = null;
$conn = null;

header('location: index.php');


