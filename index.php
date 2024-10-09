<?php
session_start(); // Iniciar sesión para manejar mensajes de error

// Llamar al fichero de conexión una vez
include_once 'connection.php';

// Sentencia SQL a ejecutar
$querySelectAll = "SELECT * FROM colores";

// Preparar la ejecución
$querySelectAll = $conn->prepare($querySelectAll);

// Ejecución de la petición a la base de datos
$querySelectAll->execute();

// Guardar el resultado como array asociativo
$resultado = $querySelectAll->fetchAll(); 

// Lista de colores válidos
$coloresValidos = ['red', 'green', 'blue', 'yellow', 'pink', 'white'];
$errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : ''; // Recuperar el mensaje de error
unset($_SESSION['error']); // Limpiar el mensaje de error

if ($_POST) {
    // Guardar los valores introducidos por el usuario
    $user = trim($_POST['usuario']);
    $color = trim($_POST['color']);

    // Validar campos vacíos
    if (empty($user) || empty($color)) {
        $_SESSION['error'] = 'Los campos no pueden estar vacíos.';
    } elseif (!in_array(strtolower($color), $coloresValidos)) {
        $_SESSION['error'] = 'No existe el color indicado.';
    } else {
        // Insertar en la base de datos
        $queryInsert = "INSERT INTO colores (usuario, color) VALUES(?, ?)";
        $sqlInsert = $conn->prepare($queryInsert);
        $sqlInsert->execute(array($user, $color));

        // Resetear el query
        $sqlInsert = null;
        $conn = null;

        // Refrescar la página
        header('location: index.php');
        exit;
    }
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
        <h1>Colores preferidos</h1>
    </header>
    <main>
        <section>
            <h2>Colores elegidos</h2>
            <div>
                <?php foreach ($resultado as $fila) : ?>
                <div class="respuesta" style="color: <?= htmlspecialchars($fila['color']) ?>; border-color: <?= htmlspecialchars($fila['color']) ?>;">
                   <p><?= htmlspecialchars($fila['usuario']) ?> : <?= htmlspecialchars($fila['color']) ?></p> 
                   <p>
                    <a href="index.php?id=<?= htmlspecialchars($fila['id_colores']) ?>&user=<?= htmlspecialchars($fila['usuario']) ?>&color=<?= htmlspecialchars($fila['color']) ?>" id="edicion<?=($fila['id_colores'])?>">
                        <span style="color: <?= htmlspecialchars($fila['color']) ?>;"><i class="fa-solid fa-pen"></i></span>
                    </a>
                    <a href="delete.php?id=<?= htmlspecialchars($fila['id_colores']) ?>" id="basura<?=($fila['id_colores'])?>">
                        <span style="color: <?= htmlspecialchars($fila['color']) ?>;" class="icon"><i class="fa-solid fa-trash"></i></span>
                    </a>
                   </p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section>
            <?php if(!isset($_GET['id'])) : ?>
            <h2>Introduce tu color preferido</h2>
            <form method="post">
                <label for="usuario">Escribe tu nombre</label>
                <input type="text" name="usuario" id="usuario" required>
                <label for="color">Tu color favorito es...</label>
                <input type="text" name="color" id="color" required>
                <div class="error" style="color: red;">
                    <p><?= htmlspecialchars($errorMessage) ?></p>
                </div>
                <div>
                    <button type="submit">Enviar</button>
                    <a href="index.php"><button type="button">Cancelar</button></a>
                </div>
            </form>
            <?php endif; ?>

            <?php if(isset($_GET['id'])) : ?>
            <h2>Modifica tus preferencias</h2>
            <form method="get" action="update.php">
                <label for="usuario">Edita tu nombre</label>
                <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($_GET['id']); ?>">
                <input type="text" name="usuario" id="usuario" value="<?= isset($_GET['user']) ? htmlspecialchars($_GET['user']) : ''; ?>" required>
                <label for="color">Edita tu color favorito es...</label>
                <input type="text" name="color" id="color" value="<?= isset($_GET['color']) ? htmlspecialchars($_GET['color']) : ''; ?>" required>
                <div class="error" style="color: red; display: <?= $errorMessage ? 'block' : 'none' ?>;">
                    <p>No se permite ese color</p>
                </div>
                <div>
                    <button type="submit">Editar</button>
                    <button type="submit" name="reset" value="reset">Cancelar</button>
                </div>
                
            </form>
            <?php endif; ?>
        </section>
    </main>
</body>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll('.delete-link');

    links.forEach(link => {
        const normalIcon = link.querySelector('i.fa-trash');
        const beatFadeIcon = document.createElement('i');

        beatFadeIcon.className = 'fa-solid fa-trash fa-beat-fade icon-beat-fade';
        beatFadeIcon.style.opacity = 0; // Inicialmente oculto
        link.querySelector('.icon').appendChild(beatFadeIcon);

        link.addEventListener('mouseover', () => {
            normalIcon.style.opacity = 0; // Oculta el icono normal
            beatFadeIcon.style.opacity = 1; // Muestra el icono con efecto
        });

        link.addEventListener('mouseout', () => {
            normalIcon.style.opacity = 1; // Muestra de nuevo el icono normal
            beatFadeIcon.style.opacity = 0; // Oculta el icono con efecto
        });
    });
});
</script>


</html>
