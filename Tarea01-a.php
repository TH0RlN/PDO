<?php
    try
    {
        $db = new PDO('mysql:dbname=recetas;host=localhost', 'root',  '');    
    }
    catch (PDOException $e)
    {
        die('ERROR');
    }
    
    $sql = "SELECT nombre, dificultad, tiempo, (SELECT nombreartistico FROM chef WHERE codigo = cod_chef) AS chef FROM receta;";
    
    $result = $db->query($sql);
    $res_arr = $result->fetchAll(PDO::FETCH_ASSOC);
    
    unset($result);
    unset($db);
    
    $str = "<table><tr><th>Nombre</th><th>Dificultad</th><th>Tiempo</th><th>Chef</th></tr>";
    
    foreach ($res_arr as $fila)
        $str .= "<tr><td>" . $fila['nombre'] . "</td><td>" . $fila['dificultad'] . "</td><td>" . $fila['tiempo'] . "</td><td>" . $fila['chef'] . "</td></tr>";
    
    $str .= "</table>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tareas.css">
    <title>Tarea 01</title>
</head>
<body>
    <?php echo $str;?>
</body>
</html>
