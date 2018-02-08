<?php
require_once 'conectarPDO.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


session_start();

$con = conectarDb($_SESSION['conexion']['host'], $_SESSION['conexion']['database'], $_SESSION['conexion']['user'], $_SESSION['conexion']['password']);

$sentencia = "show tables";
$resultado = consulta($con, $sentencia);
foreach ($resultado as $value) {
    $arrayTablas[] = $value;
}


if (isset($_POST['volver1'])) {

    header("Location:index.php");
}

if (isset($_POST['tabla'])) {

    $_SESSION['conexion']['tabla'] = filter_input(INPUT_POST, 'tabla');
    header("Location:gestionarTablas.php");
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Tablas</title>
        <link rel="stylesheet" type="text/css" href="proyecto.css">
    </head>
    <body>
        <div id="principal">
            <h1>Conectado a <?php echo $_SESSION['conexion']['database'] ?></h1>
            <form action="tablas.php" method="POST">
                <input type="submit" name="volver1" value="Volver">
            </form>
        </div>
        <div id="contenido">
            <form action="tablas.php" method="POST">
                <?php
                foreach ($arrayTablas as $values => $value) {
                    foreach ($value as $tabla) {
                        echo "<input type='submit' name='tabla' value='$tabla'>";
                    }
                }
                ?>   
            </form>

        </div>
    </body>
</html>

