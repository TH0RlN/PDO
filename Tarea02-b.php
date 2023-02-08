<?php
    $str = "";
    try
    {
        $db = new PDO('mysql:dbname=recetas;host=localhost', 'root',  '');
        if (!empty($_GET['cocinero']))
        {
            $sql_usuario = "SELECT * FROM chef WHERE codigo=?";
            $sql_provincias = "SELECT * FROM provincia";
            
            $prep_usuario = $db->prepare($sql_usuario);
            $prep_usuario->execute(array($_GET['cocinero']));
            $prep_provincias = $db->prepare($sql_provincias);
            $prep_provincias->execute();
            
            $res_usuario = $prep_usuario->fetch(PDO::FETCH_ASSOC);
            $res_provincias = $prep_provincias->fetchAll(PDO::FETCH_ASSOC);

            $str = "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
            $str .= '<h1>Editar cocinero</h1>
                        <table>
                            <tr>
                                <td><label for="cod">Código:</label></td>
                                <td><input type="number" name="cod" id="cod" disabled value="' . $res_usuario['codigo'] .'"></td>
                                <td></td>
                            </tr>
                            <tr>
                            <td><label for="name">Nombre:</label></td>
                            <td><input type="text" name="name" id="name" value="' . $res_usuario['nombre'] . '"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="ndname">Apellidos:</label></td>
                            <td><input type="text" name="ndname1" id="ndname1" value="' . $res_usuario['apellido1'] . '"></td>
                            <td><input type="text" name="ndname2" id="ndname2" value="' . $res_usuario['apellido2'] . '"></td>
                        </tr>
                        <tr>
                            <td><label for="artname">Nombre artístico:</label></td>
                            <td><input type="text" name="artname" id="artname" value="' . $res_usuario['nombreartistico'] . '"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="sex">Sexo:</label></td>
                            <td>
                                <select name="sex" id="sex">
                                    <option value="H" ' . ($res_usuario['sexo'] == 'H' ? 'selected' : '') . '>Hombre</option>
                                    <option value="M" ' . ($res_usuario['sexo'] == 'M' ? 'selected' : '') . '>Mujer</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="bdate">Fecha de nacimiento:</label></td>
                            <td><input type="date" name="bdate" id="bdate" value="' . $res_usuario['fecha_nacimiento'] . '"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="place">Localidad:</label></td>
                            <td><input type="text" name="place" id="place" value="' . $res_usuario['localidad'] . '"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="province">Provincia:</label></td>
                            <td>
                                <select name="province" id="province">';

            foreach ($res_provincias as $prov)
                $str .=                   '<option value="' . $prov['codigo'] . '" ' . ($prov['codigo'] == $res_usuario['cod_provincia'] ? 'selected' : '') . '>' . $prov['nombre'] . '</option>';
            $str .=             '</select>
                            </td>
                        </tr>
                    </table>';

            $str .= "<input type='submit' value='Guardar'>";
            $str .= "</form>";
            $str .= "<a href='" . $_SERVER['PHP_SELF'] . "?cocinero=" . $res_usuario['codigo'] . "&del=t'><button>Eliminar</button></a>";
            $str .= "<a href='" . $_SERVER['PHP_SELF'] . "'><button>Cancelar</button></a>";
            
            unset($prep_usuario);
            unset($db);
        }
        else
        {
            $sql_usuario ="SELECT nombre, apellido1, apellido2, nombreartistico, codigo FROM chef";

            $prep_usuario = $db->prepare($sql_usuario);
            $prep_usuario->execute();

            $str = "<table><tr><th>Nombre</th><th>Apellidos</th><th>Nombre Artístico</th><th></th><tr>";
            while ($aux = $prep_usuario->fetch(PDO::FETCH_NUM))
                    $str .= "<tr><td>" . $aux[0] ."</td><td>" . implode (" ", array($aux[1], $aux[2])) ."</td><td>" . $aux[3] ."</td><td><a href='?cocinero=" . $aux[4] . "'>Editar</a></td></tr>";
            $str .= "</table>";

            unset($prep_usuario);
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
    
