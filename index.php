<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNU</title>
</head>
<body>
    <h1>CNU - Plataforma de Cursos</h1>
    <?php
        session_start();
        if (isset($_SESSION['usuario'])) {
            require_once "views/bienvenidaIndex.php";
        }
        else{
            require_once "views/formularioSignup.php";
        }
    ?>
    <p>a</p>
</body>
</html>