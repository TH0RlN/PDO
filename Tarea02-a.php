<?php
    try
    {
        $db = new PDO('mysql:dbname=recetas;host=localhost', 'root',  '');    
    }
    catch (PDOException $e)
    {
        die('ERROR');
    }
    
    if (!empty($_GET['recipe']))
    {
        $sql = "SELECT (SELECT nombre FROM ingrediente WHERE codigo = cod_ingrediente), cantidad, medida FROM `receta_ingrediente` WHERE cod_receta=?";
        
        $db->prepare($sql);
    }
    else
    {
        $columns = ['nombre', 'dificultad', 'tiempo', 'chef'];

        $sql = "SELECT nombre, dificultad, tiempo, (SELECT nombreartistico FROM chef WHERE codigo = cod_chef) AS chef FROM receta;";

        $result = $db->prepare($sql);
        $result->execute();

        foreach ($columns as $column)
            $result->bindColumn($column, $$column);

        $res_arr = [];

        while ($result->fetch(PDO::FETCH_BOUND))
            array_push($res_arr, array($nombre, $dificultad, $tiempo, $chef));

        unset($result);
        unset($db);

        $str = "<table><tr><th>Nombre</th><th>Dificultad</th><th>Tiempo</th><th>Chef</th></tr>";

        foreach ($res_arr as $fila)
            $str .= "<tr><td>" . $fila[0] . "</td><td>" . $fila[1] . "</td><td>" . $fila[2] . "</td><td>" . $fila[3] . "</td></tr>";

        $str .= "</table>";
    }
    
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
