<?php
require_once 'conectarPDO.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


session_start();

$con = conectarDb($_SESSION['conexion']['host'], $_SESSION['conexion']['database'], $_SESSION['conexion']['user'], $_SESSION['conexion']['password']);

$tabla = $_SESSION['conexion']['tabla'];

$sentencia = "select * from $tabla";

$resultado = consulta($con, $sentencia);

foreach ($resultado as $datos) {
    $arrayDatos[] = $datos;
}


if (isset($_POST['volver2'])) {

    header("Location:tablas.php");
}
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Gestionar Tablas</title>
        <link rel="stylesheet" type="text/css" href="proyecto.css">
    </head>
    <body>
        <div id="principal">
            <h1>Conectado a <?php echo $_SESSION['conexion']['database'] ?></h1>
            <form action="tablas.php" method="POST">

                <input type="submit" name="volver2" value="Volver">
            </form>
        </div>
        <div id="contenido">

            <h2>Tabla <?php echo $_SESSION['conexion']['tabla'] ?></h2>
            <table border="1">

                <?php
                foreach ($arrayDatos as $values) {
                    echo"<tr>";
                    foreach ($values as $value => $dato) {
                        $indice[] = $value;
                        echo "<th> $value</th>";
                    }
                    echo"<th>Opciones</th>";
                    echo"</tr>";
                    break;
                }


                foreach ($arrayDatos as $values) {
                    echo"<tr>";
                    echo"<form action='editar.php' method='POST'>";

                    foreach ($values as $value => $dato) {

                        echo "<td> $dato</td>";
                        echo"<input type='hidden' name='fila[$value]' value='$dato'>";
                    }
                    echo"<td>"
                    . "<input type='submit' name='editar' value='Delete'>"
                    . "<input type='submit' name='editar' value='Modify'>"
                    . "</form></td>";

                    echo"</tr>";
                }
                ?>   
            </table>

            <form action="editar.php" method="POST">

                <input type="submit" name="editar" value="Insert">

                <?php
                foreach ($indice as $index => $valor) {
                    echo "<input type='hidden' name='indices[$index]' value='$valor'>";
                }
                ?>    
            </form>
        </div>
    </body>
</html>
