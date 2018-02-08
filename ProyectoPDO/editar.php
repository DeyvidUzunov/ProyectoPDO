<?php
require_once 'conectarPDO.php';

session_start();

$con = conectarDb($_SESSION['conexion']['host'], $_SESSION['conexion']['database'], $_SESSION['conexion']['user'], $_SESSION['conexion']['password']);

$tabla = $_SESSION['conexion']['tabla'];

$array = $_POST['fila'];
$indices = $_POST['indices'];
if ($indices != null) {
    $_SESSION['conexion']['indices'] = $indices;
}

if ($array != null) {
    $_SESSION['conexion']['datos'] = $array;
}
$formulario = null;

switch ($_POST['editar']) {
    case 'Delete':
        $msj = borrar($array, $tabla, $con);
        header("refresh:3; url=gestionarTablas.php");
        break;

    case 'Modify':

        $formulario = mostrarFormularioModify($array);

        break;

    case 'Insert':

        $formulario = mostrarFormularioNuevo($indices);

        break;
}

if (isset($_POST['insertar'])) {
    $sentencia = "Insert into $tabla values(";
    $indices = $_SESSION['conexion']['indices'];

    foreach ($indices as $index => $dato) {
        $indices[] = $dato;
        $sentencia .= " ?,";
    }
    $sentencia = substr($sentencia, 0, sizeof($sentencia) - 2);
    $sentencia .= ")";
    var_dump($sentencia);
    $valores = $_POST['dato'];
//    $i=0;
//    foreach ($indices as $indice){
//        $datos[]=$valores[$i];
//        $i++;
//    }
    var_dump($valores);

    $resultado = modificacion($con, $sentencia, $valores);

    if ($resultado) {
        $msj = "Añadido correctamente";
    } else {
        $msj = "Ocurrio un error";
    }
}

if (isset($_POST['aceptar'])) {
    $sentencia = "Update $tabla set ";
    $array = $_SESSION['conexion']['datos'];
    foreach ($array as $indice => $dato) {
        $indices[] = $indice;
        $sentencia .= "?=?, ";
    }
    $sentencia = substr($sentencia, 0, sizeof($sentencia) - 3);
    $sentencia .= " where ?=?";

    $valores = $_POST['dato'];
    $i = 0;
    foreach ($indices as $datoBueno) {

        $datos[] = $datoBueno;
        $datos[] = $valores[$i];
        $i++;
    }
    $datos[] = $datos[0];
    $datos[] = $valores[0];

    var_dump($sentencia);
    var_dump($datos);
    $resultado = modificacion($con, $sentencia, $datos);

    if ($resultado) {
        $msj = "Cambio realizado";
        header("refresh:3; url=gestionarTablas.php");
    } else {
        $msj = "algo fallo";
    }
}

if (isset($_POST['cancelar'])) {

    header("Location:gestionarTablas.php");
}

/**
 * 
 * @param type $array
 * @return string
 * @description Muestra el formulario de la fila que se vaya a modificar
 */
function mostrarFormularioModify($array) {

    $formulario = "<form action='editar.php' method='POST'>";
    foreach ($array as $indices => $datos) {

        $formulario .= "$indices <input type='text' name='dato[]' value='$datos'></br>";
    }

    $formulario .= "<input type='submit' name='aceptar' value='Aceptar'>"
            . "<input type='submit' name='cancelar' value='Cancelar'></form>";

    return $formulario;
}

function mostrarFormularioNuevo($indices) {
    $formulario = "<form action='editar.php' method='POST'> ";
    foreach ($indices as $indice => $dato) {
        $formulario .= "Indica el $dato:<input type='text' name='dato[]' value=''></br>";
    }
    $formulario .= "<input type='submit' name='insertar' value='Insertar'>"
            . "<input type='submit' name='cancelar' value='Cancelar'></form>";
    return $formulario;
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar</title>
        <link rel="stylesheet" type="text/css" href="proyecto.css">
    </head>
    <body>
        <div id="principal">
            <h1>Conectado a <?php echo $_SESSION['conexion']['database'] ?></h1>
            <form action="tablas.php" method="POST">
                <input type="submit" name="volver3" value="Volver">
            </form>
            <?php echo "<h2>" . $msj . "</h2>"; ?>
        </div>
        <div id="contenido">
            <?php
            if ($formulario) {
                echo"$formulario";
            }
            ?>


        </div>
    </body>
</html>