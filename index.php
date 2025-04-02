<?php 
session_start();
include('includes/header.php');
include('db/conection.php'); 
?>

<style>
    .logo-container {
        text-align: center;
        margin-top: 10px;
    }

    .logo-container img {
        max-width: 100px;
    }

    .alert-custom {
        font-size: 1.2rem;
        font-weight: bold;
    }

    .highlight-text {
        font-size: 2rem;
        font-weight: 700;
        color: #2f6627;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .alert-success {
    background-color: rgba(29, 141, 55, 0.85) !important; /* Verde más suave con transparencia */
    color: #ffffff !important;                            /* Letras blancas */
    border: 2px solid #1e7e34;
}

.highlight-text {
    font-size: 2.2rem;
    font-weight: 900;
    color:rgb(0, 0, 0);
    text-shadow: 1px 1px 2px #14532d; /* Un toque de sombra para destacar */
}


</style>

<div class="container my-3">
    <div class="logo-container">
        <img src="assets/media/vracad_preview.png" alt="Logo">
    </div>

    <h3 class="text-center mt-3 mb-4">Registro de Asistencia</h3>

    <!-- FORMULARIO -->
    <form class="row g-3 justify-content-center mb-4" action="index.php" method="POST">
        <div class="col-md-6">
            <input class="form-control form-control-lg" type="text" id="dni" name="query" maxlength="8" required autofocus placeholder="Ingrese DNI del ingresante">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-lg w-100" type="submit">Buscar</button>
        </div>
    </form>

    <?php
    if (isset($_POST["query"])) {
        $dnibusqueda = $_POST["query"];
        $estado = 1;

        $sql = "SELECT * FROM alumnos a INNER JOIN program p ON a.id_progariel = p.prog_id WHERE dni = '$dnibusqueda'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $check = mysqli_query($conn, "SELECT * FROM asistencia WHERE dni = '$dnibusqueda'");
            $count = mysqli_num_rows($check);

            if ($count > 0) {
                $_SESSION['color'] = "warning";
                $_SESSION['mensaje'] = "⚠️ INGRESANTE YA REGISTRADO";
            } else {
                mysqli_query($conn, "INSERT INTO asistencia(dni,estado,fecharegistro) VALUES('$dnibusqueda','$estado',CURRENT_TIMESTAMP())");
                $_SESSION['color'] = "success";
                $_SESSION['mensaje'] = "✅ ASISTENCIA REGISTRADA CON ÉXITO";
            }

            $_SESSION['mesa'] = $row["mesa"];
            $_SESSION['id_firma'] = $row["id_firma"];
            $_SESSION['dni'] = $row["dni"];
            $_SESSION['nombres'] = "{$row["paterno"]} {$row["materno"]} {$row["nombres"]}";
            $_SESSION['programa'] = $row["programa"];
        } else {
            $_SESSION['color'] = "danger";
            $_SESSION['mensaje'] = "❌ NO ES INGRESANTE DEL 2025-I";
        }

        header("Location: index.php");
        exit();
    }

    if (isset($_SESSION['mensaje'])) {
        echo "<div class='alert alert-{$_SESSION['color']} alert-custom text-center'>";
        echo $_SESSION['mensaje'];

        if ($_SESSION['color'] !== 'danger') {
            echo "<p class='highlight-text my-3'>Mesa: <strong>{$_SESSION['mesa']}</strong> &nbsp;&nbsp; N°: <strong>{$_SESSION['id_firma']}</strong></p>";
            
       
            echo "<p><strong style='color:#333;'>Nombres:</strong> <span style='font-weight: 600;'>{$_SESSION['nombres']}</span></p>";
            echo "<p><strong style='color:#333;'>Programa:</strong> <span style='font-weight: 600;'>{$_SESSION['programa']}</span></p>";
            echo "<p><strong style='color:#333;'>DNI:</strong> <span style='font-weight: 600;'>{$_SESSION['dni']}</span></p>";

            
        }

        echo "</div>";
        session_unset();
    }

    // Últimos ingresos
    $ultimos = mysqli_query($conn, "SELECT a.dni, a.fecharegistro, al.paterno, al.materno, al.nombres, al.mesa, al.id_firma, al.programa 
                                    FROM asistencia a 
                                    JOIN alumnos al ON a.dni = al.dni 
                                    ORDER BY a.fecharegistro DESC LIMIT 5");

    if (mysqli_num_rows($ultimos) > 0) {
        echo "<div class='mt-5'>";
        echo "<h5 class='text-center'><i class='bi bi-clock'></i> Últimos Ingresos</h5>";
        echo "<table class='table table-bordered table-hover text-center'>";
        echo "<thead class='table-light'><tr>
                <th>#</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Mesa</th>
                <th>N°</th>
                <th>Programa</th>
                <th>Hora</th>
              </tr></thead><tbody>";
        $n = 1;
        while ($row = mysqli_fetch_assoc($ultimos)) {
            echo "<tr>
                    <td>$n</td>
                    <td>{$row['dni']}</td>
                    <td>{$row['paterno']} {$row['materno']} {$row['nombres']}</td>
                    <td>{$row['mesa']}</td>
                    <td>{$row['id_firma']}</td>
                    <td>{$row['programa']}</td>
                    <td>" . date('H:i:s', strtotime($row['fecharegistro'])) . "</td>
                </tr>";
            $n++;
        }
        echo "</tbody></table></div>";
    }
    ?>
</div>

<script>
    const input = document.getElementById('dni');
    input.addEventListener('input', () => {
        if (input.value.length > 8) input.value = input.value.slice(0, 8);
    });
    window.onload = () => input.focus();
</script>

<?php include('includes/footer.php'); ?>
