<?php

/**
 * 
 * @return PDO
 * @descrption retorna una conexion PDO
 */
function conectar($host, $user, $password) {
    try {
        $con = new PDO("mysql:host=$host", $user, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die(" No se conecto" . $e->getMessage());
    }
    return $con;
}

/**
 * 
 * @param type $host
 * @param type $user
 * @param type $password
 * @return \PDO conexion base de datos.
 */
function conectarDb($host, $bd, $user, $password) {
    try {
        $con = new PDO("mysql:host=$host; dbname=$bd", $user, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die(" No se conecto" . $e->getMessage());
    }
    return $con;
}

/**
 * 
 * @param PDO $con conexion
 * @param string $sentencia sentecia SQL parametrizada
 * @param array $valores los valores que le tengoq  pasar a la sentencia 
 * @return array indexado con las filas resultantes de la consulta
 * @description devuelve el resultado de una consulta en un array
 */
function consulta($con, $sentencia, $valores) {
    try {
        //obtenemos un PDOstatement
        $stmt = $con->prepare($sentencia);
        //ejecutamos la sentencia
        $stmt->execute($valores);

        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $array[] = $fila;
        }
    } catch (PDOException $ex) {
        die("Ocurrio un problema: " . $ex->getMessage());
    }
    return $array;
}

/**
 * 
 * @param PDO $con
 * @param string $sentencia
 * @param array $parametros
 * @return boolean false si no se a podido modificar y true si a ido bien
 * @description Inserta Borra o Modifica una fila
 */
function modificacion($con, $sentencia, $parametros) {

    try {
        $stmt = $con->prepare($sentencia);
        $n=0;
        foreach ($parametros as $k =>&$valor){
           $n++;
            var_dump($n);
            var_dump($valor);
                echo "bind_param($n,$valor)";
            $stmt=$con->bind_param($n,$valor);
            }
            
        

        
        $stmt->execute();

        //contamos las filas (I-D-U)
        $filas = $stmt->rowCount() ? true : false;
        var_dump($filas);
    } catch (PDOException $ex) {
        die("Ocurrio un problema: " . $ex->getMessage());
    }

    return $filas;
}

/**
 * @description Este metodo crea la sentencia necesaria en cada caso para borrar una fila
 * @param type $array
 * @param type $tabla
 * @param type $con
 * @return string devuelve mensaje de exito o error.
 */
function borrar($array, $tabla, $con) {
    var_dump($array);
    $sentencia = "DELETE from $tabla where ";

    foreach ($array as $value => $dato) {

        $sentencia .= "?=? and ";
        $indices[] = $value;
        $indices[] = $dato;
    }
    var_dump($sentencia);
    $sentenciaBuena = substr($sentencia, 0, sizeof($sentencia) - 5);

    var_dump($sentenciaBuena);

    $resultado = modificacion($con, $sentenciaBuena, $indices);

    if ($resultado) {
        $msj = "La linea seleccionada a sido eliminada";
    } else {
        $msj = "A ocurrido un grave error";
    }
    return $msj;
}

?>
