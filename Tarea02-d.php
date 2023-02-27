<?php
    session_start();

    $f = fopen("db.json", "r");

    $db_data = json_decode(fread($f, filesize("db.json")))[0];
    fclose($f);

    $str = "";
    try
    {
        $db = new PDO($db_data->conect, $db_data->user, $db_data->passw);
        if (!empty($_POST['submit']))
        {
            $data = ['nombre', 'apellido1', 'apellido2', 'nombreartistico', 'sexo', 'fecha_nacimiento', 'cod_provincia'];
            if (!empty($_SESSION['id']))
            {
                $sql = "UPDATE chef SET";
                foreach ($data as $datum)
                    $sql .= " " . $datum . "=?,";
                $sql = trim($sql, ',');
                $sql .= " WHERE codigo=?";

                $prep = $db->prepare($sql);
                $prep->execute(array($_POST['nombre'], $_POST['apellido1'], $_POST['apellido2'], $_POST['nombreartistico'], $_POST['sexo'], $_POST['fecha_nacimiento'], $_POST['cod_provincia'], $_SESSION['id']));

                $n = $prep->rowCount();

                unset($prep);
                unset($db);

                $str = "
                <htlm>
                    <head>
                        <title>Resultado</title>
                    </head>
                    <body>
                        <h1>Resultado</h1>
                        <p>Se han actualizado $n registros.</p>
                        <a href='Tarea02-d.php'>Volver</a>
                    </body>
                    <footer>
                        <script>
                            setTimeout(function() {
                                window.location.href = 'Tarea02-d.php';
                            }, 4000);
                        </script>
                    </footer>
                </html>";

                //header('Location: Tarea02-d.php');
            }
        }
        else
        {
            if (!empty($_GET['cocinero']))
            {
                $_SESSION['id'] = $_GET['cocinero'];

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
                                    <td><label for="codigo">Código:</label></td>
                                    <td><input type="number" name="codigo" id="codigo" disabled value="' . $res_usuario['codigo'] .'"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                <td><label for="nombre">Nombre:</label></td>
                                <td><input type="text" name="nombre" id="nombre" value="' . $res_usuario['nombre'] . '"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="apellido">Apellidos:</label></td>
                                <td><input type="text" name="apellido1" id="apellido1" value="' . $res_usuario['apellido1'] . '"></td>
                                <td><input type="text" name="apellido2" id="apellido2" value="' . $res_usuario['apellido2'] . '"></td>
                            </tr>
                            <tr>
                                <td><label for="nombreartistico">Nombre artístico:</label></td>
                                <td><input type="text" name="nombreartistico" id="nombreartistico" value="' . $res_usuario['nombreartistico'] . '"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="sexo">Sexo:</label></td>
                                <td>
                                    <select name="sexo" id="sexo">
                                        <option value="H" ' . ($res_usuario['sexo'] == 'H' ? 'selected' : '') . '>Hombre</option>
                                        <option value="M" ' . ($res_usuario['sexo'] == 'M' ? 'selected' : '') . '>Mujer</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="fecha_nacimiento">Fecha de nacimiento:</label></td>
                                <td><input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="' . $res_usuario['fecha_nacimiento'] . '"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="place">Localidad:</label></td>
                                <td><input type="text" name="place" id="place" value="' . $res_usuario['localidad'] . '"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="cod_provincia">Provincia:</label></td>
                                <td>
                                    <select name="cod_provincia" id="cod_provincia">';

                foreach ($res_provincias as $prov)
                    $str .=                   '<option value="' . $prov['codigo'] . '" ' . ($prov['codigo'] == $res_usuario['cod_provincia'] ? 'selected' : '') . '>' . $prov['nombre'] . '</option>';
                $str .=             '</select>
                                </td>
                            </tr>
                        </table>';

                $str .= "<input type='submit' value='Guardar' name='submit' id='submit'>";
                $str .= "</form>";
                $str .= "<a href='" . $_SERVER['PHP_SELF'] . "?cocinero=" . $res_usuario['codigo'] . "&del=t'><button disabled>Eliminar</button></a>";
                $str .= "<a href='" . $_SERVER['PHP_SELF'] . "'><button>Cancelar</button></a>";
                
                unset($prep_provincias);
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
    }
    catch (PDOException $e)
    {
        die($e->getMessage());
    }
    catch (Exception $e)
    {
        die($e->getMessage());
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
    
