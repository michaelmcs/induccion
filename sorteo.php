<?php
session_start();
include('includes/header.php');
include('db/conection.php');

// Limpiar filtrados si cambia de programa
if (isset($_POST['programa']) && $_POST['programa'] != "0" && !isset($_POST['sortear']) && !isset($_POST['guardar'])) {
    unset($_SESSION['filtrados']);
    unset($_SESSION['filtrados_originales']);
    $filtro_programa = $_POST['programa'];
    $sql = "SELECT * FROM alumnos a 
            INNER JOIN asistencia asis ON a.dni = asis.dni 
            INNER JOIN program p ON a.id_progariel = p.prog_id 
            WHERE a.id_progariel= '$filtro_programa'";
    $result = $conn->query($sql);
    $_SESSION['filtrados_originales'] = [];
    $_SESSION['filtrados'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['filtrados_originales'][] = $row;
        $_SESSION['filtrados'][] = $row;
    }
}

if (isset($_POST['sortear']) && isset($_SESSION['filtrados_originales'])) {
    $filtrados = $_SESSION['filtrados_originales'];
    shuffle($filtrados);
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 10;
    $cantidad = min($cantidad, count($filtrados));
    $filtrados = array_slice($filtrados, 0, $cantidad);
    $_SESSION['filtrados'] = $filtrados;
}

if (isset($_POST['guardar']) && isset($_SESSION['filtrados'])) {
    $filtrados = $_SESSION['filtrados'];
    foreach ($filtrados as $row) {
        if ($row) {
            $dni = $row["dni"];
            $prog_id = $row["id_progariel"];
            $fecha = date('Y-m-d H:i:s');
            $insert_sql = "INSERT INTO sorteos_guardados (dni, prog_id, fecha) VALUES ('$dni', '$prog_id', '$fecha')";
            $conn->query($insert_sql);
        }
    }
    echo "<div class='alert alert-success'>Ganadores guardados exitosamente.</div>";
}
?>

<!-- HTML principal -->
<style>
    #sorteoTable td {
        font-size: 18px;
        padding: 10px;
    }
    #sorteoTable th {
        font-size: 16px;
        text-align: center;
    }
</style>
<div class="container">
    <h4 class="text-center py-3">Sorteo de Suvenirs</h4>
    <form action="" method="post">
        <div class="row">
            <div class="col-md-6">
                <label class="h5" for="programa">Programa:</label>
                <select class="form-select" name="programa" id="programa">
                    <option value="0">SELECCIONE UN PROGRAMA</option>
                    <?php
                    $sql_prog = "SELECT prog_id, programa FROM program ORDER BY programa ASC";
                    $result_prog = $conn->query($sql_prog);
                    if ($result_prog && $result_prog->num_rows > 0) {
                        while ($row_prog = $result_prog->fetch_assoc()) {
                            $selected = (isset($_POST['programa']) && $_POST['programa'] == $row_prog['prog_id']) ? 'selected' : '';
                            echo "<option value='{$row_prog['prog_id']}' $selected>{$row_prog['programa']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="h5" for="cantidad">Cantidad de sorteados:</label>
                <input class="form-control" type="number" name="cantidad" id="cantidad" value="<?php echo isset($_POST['cantidad']) ? $_POST['cantidad'] : 10; ?>" min="1">
            </div>
        </div>

        <div class="py-2 text-center">
            <input class="btn btn-warning" type="submit" name="sortear" value="Sortear">
            <input class="btn btn-primary" type="submit" name="guardar" value="Guardar">
            <button id="pdfButton" class="btn btn-danger" style="display: none;">Generar PDF</button>
        </div>
    </form>

    <div class="container">
        <table id="sorteoTable" class="display" style="width:100%">
            <thead>
                <tr class="table-dark">
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Programa</th>
                    <th>Firma</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['filtrados'])) {
                    $contador = 1;
                    foreach ($_SESSION['filtrados'] as $row) {
                        if ($row) {
                            echo "<tr>";
                            echo "<td>{$contador}</td>";
                            echo "<td>{$row['dni']}</td>";
                            echo "<td>{$row['nombres']}</td>";
                            echo "<td>{$row['paterno']}</td>";
                            echo "<td>{$row['materno']}</td>";
                            echo "<td>{$row['programa']}</td>";
                            echo "<td style='padding-top: 25px;'>_________________________</td>";
                            echo "</tr>";
                            $contador++;
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS y DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#programa').select2({
            placeholder: "Seleccione un programa",
            allowClear: true,
            width: '100%'
        });

        $('#programa').on('change', function () {
            $('form').submit();
        });

        var programaTexto = $('#programa option:selected').text();
        if (programaTexto === "SELECCIONE UN PROGRAMA") programaTexto = "";

        $('#sorteoTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: 'Exportar a PDF',
                    title: 'Lista de Ganadores del Sorteo ' + programaTexto,
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function (doc) {
                        doc.content[1].table.widths = ['5%', '15%', '20%', '15%', '15%', '20%', '10%'];
                        doc.styles.tableHeader.fontSize = 10;
                        doc.styles.tableBodyEven.fontSize = 9;
                        doc.styles.tableBodyOdd.fontSize = 9;
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
                        doc.content[1].margin = [10, 0, 10, 0];
                        var tableBody = doc.content[1].table.body;
                        for (var i = 1; i < tableBody.length; i++) {
                            tableBody[i][6].text = "_________________________";
                            tableBody[i][6].margin = [0, 15, 0, 0];
                        }
                    }
                }
            ]
        });

        if ('<?php echo isset($_POST['guardar']) ? '1' : ''; ?>' === '1') {
            $('#pdfButton').show();
        }
    });
</script>

<?php include("includes/footer.php") ?>
