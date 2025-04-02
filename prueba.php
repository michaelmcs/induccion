<!DOCTYPE html>
<html>
<head>
    <title>Sorteo y Guardado de Ganadores</title>
</head>
<body>

<h1>Sorteo y Guardado de Ganadores</h1>

<form action="" method="post">
    <label for="programa_id">Filtrar por Programa ID:</label>
    <select name="programa_id" id="programa_id">
        <?php
        for ($i = 1; $i <= 45; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        ?>
    </select>
    <input type="submit" name="filtrar" value="Filtrar">
</form>

<form action="" method="post">
    <input type="submit" name="sortear" value="Sortear">
</form>

<form action="" method="post">
    <input type="submit" name="guardar" value="Guardar Ganadores">
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <!-- Agrega más columnas según tu esquema de base de datos -->
    </tr>

    <?php
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ingresantes";


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if(isset($_POST['filtrar'])) {
        $programa_id = $_POST['programa_id'];
        $sql = "SELECT * FROM alumnos WHERE programa_id = '$programa_id'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
     
            // Agrega más columnas según tu esquema de base de datos
            echo "</tr>";
        }
    }

    if(isset($_POST['sortear'])) {
        if(isset($result) && $result->num_rows > 0) {
            $filtered_rows = $result->fetch_all(MYSQLI_ASSOC);
            shuffle($filtered_rows); // Reordena los registros aleatoriamente
            $selected_winners = array_slice($filtered_rows, 0, 10); // Selecciona los primeros 10 ganadores
        }
        
        if(isset($selected_winners)) {
            foreach ($selected_winners as $winner) {
                echo "<tr>";
                echo "<td>" . $winner["id"] . "</td>";
       
                // Agrega más columnas según tu esquema de base de datos
                echo "</tr>";
            }
        }
    }


    $conn->close();
    ?>
</table>

</body>
</html>