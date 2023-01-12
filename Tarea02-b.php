<?php
    $str = "";
    try
    {
        $db = new PDO('mysql:dbname=recetas;host=localhost', 'root',  '');
        if (!empty($_GET['cocinero']))
        {
            $sql = "SELECT * FROM chef WHERE codigo=?";
            
            $prep = $db->prepare($sql);
            $prep->execute(array($_GET['cocinero']));
            
            $res = $prep->fetch(PDO::FETCH_ASSOC);

            $str = "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
            $str .= "";
            $str .= "<form>";
            
            unset($prep);
            unset($db);
        }
        else
        {
            $sql ="SELECT nombre, apellido1, apellido2, nombreartistico, codigo FROM chef";

            $prep = $db->prepare($sql);
            $prep->execute();

            $str = "<table><tr><th>Nombre</th><th>Apellidos</th><th>Nombre Artístico</th><th></th><tr>";
            while ($aux = $prep->fetch(PDO::FETCH_NUM))
                    $str .= "<tr><td>" . $aux[0] ."</td><td>" . implode (" ", array($aux[1], $aux[2])) ."</td><td>" . $aux[3] ."</td><td><a href='?cocinero=" . $aux[4] . "'>Editar</a></td></tr>";
            $str .= "</table>";

            unset($prep);
            unset($db);
        }
    }
    catch (PDOException $e)
    {
        die('ERROR');
    }
    
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tareas.css">
    <title>Tarea 02-b</title>
</head>
<body>
    <?php echo $str;?>
</body>
</html>
