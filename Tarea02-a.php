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
        $sql1 = "SELECT nombre FROM receta WHERE codigo=?";
        $sql2 = "SELECT (SELECT nombre FROM ingrediente WHERE codigo = cod_ingrediente) AS nombre, cantidad, medida FROM `receta_ingrediente` WHERE cod_receta=?";
        
        $prepsql1 = $db->prepare($sql1);
        $prepsql1->execute(array($_GET['recipe']));
        $prepsql2 = $db->prepare($sql2);
        $prepsql2->execute(array($_GET['recipe']));
        
        
        $str = "<h1>" . $prepsql1->fetch(PDO::FETCH_ASSOC)['nombre'] . "</h1>";
        $str .= "<ul>";
        
        while ($aux = $prepsql2->fetch(PDO::FETCH_ASSOC))
            $str .= "<li>" . $aux['nombre'] . ": " . $aux['cantidad'] . " " . $aux['medida'] . "</li>";
        $str .= "</ul>";
        
        unset($prepsql1);
        unset($prepsql2);
        unset($db);
        
        $str .= "<a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>&lt&ltVolver</a>";
    }
    else
    {
        $columns = ['nombre', 'dificultad', 'tiempo', 'chef', 'codigo'];

        $sql = "SELECT nombre, dificultad, tiempo, (SELECT nombreartistico FROM chef WHERE codigo = cod_chef) AS chef, codigo FROM receta;";

        $result = $db->prepare($sql);
        $result->execute();

        foreach ($columns as $column)
            $result->bindColumn($column, $$column);

        $str = "<table><tr><th>Nombre</th><th>Dificultad</th><th>Tiempo</th><th>Chef</th></tr>";
        while ($result->fetch(PDO::FETCH_BOUND))
            $str .= "<tr><td><a href='" . "?recipe=" . $codigo . "'>" . $nombre . "</a></td><td>" . $dificultad . "</td><td>" . $tiempo . "</td><td>" . $chef . "</td></tr>";
        $str .= "</table>";

        unset($result);
        unset($db);


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
