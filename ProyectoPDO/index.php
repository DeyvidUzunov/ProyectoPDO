
<?php
require_once 'conectarPDO.php';
$texto = true;

if (isset($_POST['conectar'])) {
    $texto = false;
    session_start();
    $_SESSION['conexion'] = ["host" => filter_input(INPUT_POST, 'host'),
        "user" => filter_input(INPUT_POST, 'user'),
        "password" => filter_input(INPUT_POST, 'password')];

    $con = conectar($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['password']);

    $sentencia = "show databases";

    $resultado = consulta($con, $sentencia);


    foreach ($resultado as $bases => $base) {

        foreach ($base as $campo => $valor) {
            $array[] = $valor;
        }
    }
}

if (isset($_POST['aceptar'])) {
    session_start();
    $_SESSION['conexion']['database'] = $_POST['seleccion'];

    header("Location:tablas.php");
}


if (isset($_POST['cancelar'])) {
    session_abort();

    header("Location:index.php");
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Pagina principal</title>
        <link rel="stylesheet" type="text/css" href="proyecto.css">
    </head>
    <body>
        <div id="principal">
            <h1>Conexion a un host:</h1>
            <form action="index.php" method="POST">
                Host:<input type="text" name="host" value="localhost">
                Usuario:<input type="text" name="user" value="root">
                Password:<input type="text" name="password" value="root">
                <input type="submit" name="conectar" value="Conectar">
            </form>
        </div>
        <div id="contenido">
            <?php
            if ($texto) {
                echo"<h2>Para mostar el contenido debes registrarte</h2>";
            }
            ?>
            <form action="index.php" method="POST">
                <?php
                foreach ($array as $value) {
                    echo "<input type='checkbox' name='seleccion' value='$value'>$value</br>";
                }

                if (isset($_POST['conectar'])):
                    ?>   

                    <input type="submit" name="aceptar" value="Conectar">
                    <input type="submit" name="cancelar" value="Cancelar">
                    <?php
                endif;
                ?>
            </form>

        </div>
    </body>
</html>
