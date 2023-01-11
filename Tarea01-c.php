<?php
    class filaReceta
    {
        public $nombre;
        public $dificultad;
        public $tiempo;
        public $chef;
        
        function capitalized_name()
        {
            return ucfirst(mb_strtolower($this->nombre));
        }
        
        function capitalized_chef()
        {
            return ucfirst(mb_strtolower($this->chef));
        }
    }


    try
    {
        $db = new PDO('mysql:dbname=recetas;host=localhost', 'root',  '');    
    }
    catch (PDOException $e)
    {
        die('ERROR');
    }
    
    $columns = ['nombre', 'dificultad', 'tiempo', 'chef'];
    
    $sql = "SELECT nombre, dificultad, tiempo, (SELECT nombreartistico FROM chef WHERE codigo = cod_chef) AS chef FROM receta;";
    
    $result = $db->prepare($sql);
    $result->execute();
    
    $str = "<table><tr><th>Nombre</th><th>Dificultad</th><th>Tiempo</th><th>Chef</th></tr>";
    
    while ($aux = $result->fetchObject("filaReceta"))
        $str .= "<tr><td>" . $aux->capitalized_name() . "</td><td>" . $aux->dificultad . "</td><td>" . $aux->tiempo . "</td><td>" . $aux->capitalized_chef() . "</td></tr>";
    $str .= "</table>";
    
    unset($result);
    unset($db);
    
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
