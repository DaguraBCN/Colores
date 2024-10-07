<?php

// Llamar al fichero de conexión una vez
include_once 'connection.php';

// Sentencia SQL a ejecutar
$querySelectAll = "SELECT * FROM colores";

// Preparar la ejecución
$querySelectAll = $conn -> prepare($querySelectAll);

// Ejecución de la petición a la base de datos
$querySelectAll -> execute();

// Guardar el resultado como array asociativo
$resultado = $querySelectAll ->  fetchAll(); 

if ($_POST) {
    // Guardar los valores introducidos por el usuario
    $user = $_POST['usuario'];
    $color = $_POST['color'];

    // Insertar en la base de datos
    $queryInsert = "INSERT INTO colores (usuario, color) VALUES(?, ?)";
    $sqlInsert = $conn -> prepare($queryInsert);
    $sqlInsert -> execute(array($user, $color));

    // Resetear el query
    $sqlInsert = null;
    $conn = null;

    // Refrescar la página
    header('location: index.php');

}

// var_dump($resultado);
if ($_GET){
    var_dump($_GET);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colores preferidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1> Colores preferidos</h1>
    </header>
    <main>
        <section>
            <h2>Colores elegidos</h2>
            <div>

                <?php foreach ($resultado as $fila) : ?>
                <div class= "respuesta" style="color: <?= $fila['color'] ?>; border-color: <?= $fila['color'] ?>;">
                   <p><?= $fila['usuario'] ?> : <?= $fila['color'] ?></p> 
                   <p>
                    <a href="index.php?id=<?= $fila['id_colores'] ?>&user=<?= $fila['usuario'] ?>&<?= $fila['color'] ?>">
                        <span style="color: <?= $fila['color'] ?>;"><i class="fa-solid fa-pen"></i></span>
                    </a>
                    <a href="delete.php?id=<?= $fila['id_colores'] ?>">
                        <span style="color: <?= $fila['color'] ?>;"><i class="fa-solid fa-trash"></i></span>
                    </a>
                   </p>
                </div>
                
                <?php endforeach; ?>
            </div>
        </section>
        <section>
            
            <?php if(!$_GET) : ?>
            <h2>Introduce tu color preferido</h2>
            <form method="post">
                <label for="usuario">Escribe tu nombre</label>
                <input type="text" name="usuario" id="usuario" required>
                <label for="color">Tu color favorito es...</label>
                <input type="text" name="color" id="color" required>
                <div class="error">
                    <p>No se permite ese color</p>
                </div>
                <button type="submit">Enviar</button>
            </form>
            <?php endif; ?>

            <?php if($_GET) : ?>
            <h2>Modifica tus preferencias</h2>
            <form method="get" action="update.php">
                <label for="usuario">Escribe tu nombre</label>
                <input type="text" name="usuario" id="usuario" required>
                <label for="color">Tu color favorito es...</label>
                <input type="text" name="color" id="color" required>
                <div class="error">
                    <p>No se permite ese color</p>
                </div>
                <button type="submit">Enviar</button>
            </form>
            <?php endif; ?>

        </section>
    </main>
</body>
</html>
