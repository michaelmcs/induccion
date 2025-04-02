<?php 
session_start(); // Iniciar la sesión
include('includes/header.php');
include('db/conection.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>
</head>
<body>

<style>
    body, html {
        height: 100%;
        margin: 0;
    }

    .bg-custom {
        background-color: #e0f7df; /* Color verde suave */
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .container {
        flex: 1; /* Asegura que el contenedor principal ocupe el espacio disponible */
    }

    .highlight {
        font-weight: bold;
    }

    .large-text {
        font-size: 32px;
    }
</style>

<div class="d-flex justify-content-center align-items-center bg-custom">
    <div class="container">
        <div class="search_container px-5">
            <div class="container px-5">
                <h4 class="text-center">Registro de Asistencia</h4>
                <br>
                <div class="container px-5">
                    <div class="container px-5">
                        <form class="row g-3" action="index.php" method="POST"> <!-- Cambiado a POST -->
                            <input class="form-control" type="text" min="1" max="8" id="dni" name="query" required autofocus placeholder="Buscar DNI">
                            <input class="btn btn-primary" type="submit" value="Buscar">
                        </form>

                        <?php
                        if (isset($_POST["query"])) {
                            $dnibusqueda = $_POST["query"];
                            $estado  = '1';

                            $sql = "SELECT * FROM alumnos a INNER JOIN program p ON a.id_progariel = p.prog_id WHERE dni = $dnibusqueda";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);

                                // Verificar si el estudiante ya está registrado
                                $query = mysqli_query($conn, "SELECT * FROM asistencia WHERE dni = '$dnibusqueda'") or die(mysqli_error($conn));
                                $count = mysqli_num_rows($query);

                                if ($count > 0) {
                                    // Estudiante ya registrado
                                    $_SESSION['color'] = "yellow";
                                    $_SESSION['mensaje'] = "INGRESANTE YA SE ENCUENTRA REGISTRADO";
                                } else {
                                    // Registrar asistencia
                                    mysqli_query($conn, "INSERT INTO asistencia(dni,estado,fecharegistro) VALUES('$dnibusqueda','$estado',CURRENT_TIMESTAMP())") or die(mysqli_error($conn));
                                    $_SESSION['color'] = "green";
                                    $_SESSION['mensaje'] = "ASISTENCIA REGISTRADA CON ÉXITO";
                                }

                                // Guardar los detalles en la sesión
                                $_SESSION['mesa'] = $row["mesa"];
                                $_SESSION['id_firma'] = $row["id_firma"];
                                $_SESSION['dni'] = $row["dni"];
                                $_SESSION['nombres'] = $row["nombres"] . " " . $row["paterno"] . " " . $row["materno"];
                                $_SESSION['programa'] = $row["programa"]; // Asignar el programa a la sesión
                            } else {
                                // No se encontró el DNI
                                $_SESSION['color'] = "red";
                                $_SESSION['mensaje'] = "NO PERTENECE A INGRESANTE 2024-II";
                            }

                            // Después de procesar la búsqueda, redireccionar para limpiar la URL
                            header("Location: index.php");
                            exit();
                        }

                        // Mostrar el cuadro si hay datos en la sesión
                        if (isset($_SESSION['mensaje'])) {
                            echo "<div class='alert' style='background-color: {$_SESSION['color']}; color: black; padding: 10px; border: 1px solid black; margin-bottom: 10px;'>";
                            echo "<p>{$_SESSION['mensaje']}</p>";
                            if ($_SESSION['color'] !== 'red') {
                                echo "<p class='large-text'>Mesa: <span class='highlight'>{$_SESSION['mesa']}</span> NUMERO: <span class='highlight'>{$_SESSION['id_firma']}</span></p>";
                                echo "<p>DNI: <span class='highlight'>{$_SESSION['dni']}</span></p>";
                                echo "<p>NOMBRES: <span class='highlight'>{$_SESSION['nombres']}</span></p>";
                                echo "<p>PROGRAMA: <span class='highlight'>{$_SESSION['programa']}</span></p>";
                            }
                            echo "</div>";

                            // Limpiar la sesión después de mostrar los datos
                            session_unset();
                        }
                        ?>
                        
                        <div class="img-center">
                            <img src="assets/media/vracad_preview.png" alt="" class="img-fluid">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var input = document.getElementById('dni');
    input.addEventListener('input', function() {
        if (this.value.length > 8)
            this.value = this.value.slice(0, 8);
    });

    // Poner el cursor automáticamente en el input al cargar la página
    window.onload = function() {
        input.focus();
    }
</script>

<?php include('includes/footer.php') ?>
</body>
</html>
