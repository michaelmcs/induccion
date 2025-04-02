<?php
session_start();
include('includes/header.php');
include('db/conection.php');

if (!isset($_SESSION['filtrados_originales'])) {
    $_SESSION['filtrados_originales'] = [];
    $_SESSION['filtrados'] = [];
}

if (isset($_POST['programa']) && !isset($_POST['sortear']) && !isset($_POST['guardar'])) {
    unset($_SESSION['filtrados']);
    unset($_SESSION['filtrados_originales']);
    $filtro_programa = $_POST['programa'];

    $where = ($filtro_programa !== '0') ? "WHERE a.id_progariel = '$filtro_programa'" : "";
    $sql = "SELECT a.dni, a.nombres, a.paterno, a.materno, p.programa
            FROM alumnos a
            INNER JOIN asistencia asis ON a.dni = asis.dni
            INNER JOIN program p ON a.id_progariel = p.prog_id
            $where";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['nombre_completo'] = $row['paterno'] . ' ' . $row['materno'] . ' ' . $row['nombres'];
        $_SESSION['filtrados_originales'][] = $row;
        $_SESSION['filtrados'][] = $row;
    }
}

if (isset($_POST['sortear']) && isset($_SESSION['filtrados_originales'])) {
    $filtrados = $_SESSION['filtrados_originales'];
    shuffle($filtrados);
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 10;
    $_SESSION['filtrados'] = array_slice($filtrados, 0, $cantidad);
}

if (isset($_POST['guardar']) && isset($_SESSION['filtrados'])) {
    $filtrados = $_SESSION['filtrados'];
    foreach ($filtrados as $row) {
        $dni = $row['dni'];
        $fecha = date('Y-m-d H:i:s');
        $conn->query("INSERT INTO ganadores (dnig, fechar) VALUES ('$dni', '$fecha')");
    }
    echo "<div class='alert alert-success'>Ganadores guardados exitosamente.</div>";
}
?>

<style>
    #sorteoTable td, #sorteoTable th {
        font-size: 13px;
        padding: 6px;
        text-align: center;
        color: #000 !important;
    }
    .table-dark {
        --bs-table-color: #000;
        background-color: #e9ecef;
    }
</style>
<div class="container mt-3">
    <h4 class="text-center mb-3">Sorteo de Suvenirs</h4>
    <form method="post" class="row align-items-end g-2">
        <div class="col-md-4">
            <label>Programa:</label>
            <select class="form-select select2" name="programa" id="programa">
                <option value="0">Todos los programas</option>
                <?php
                $res_prog = $conn->query("SELECT prog_id, programa FROM program ORDER BY programa");
                while ($p = $res_prog->fetch_assoc()) {
                    $selected = (isset($_POST['programa']) && $_POST['programa'] == $p['prog_id']) ? 'selected' : '';
                    echo "<option value='{$p['prog_id']}' $selected>{$p['programa']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <label>Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" value="<?php echo $_POST['cantidad'] ?? 10; ?>" min="1">
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-warning" name="sortear">Sortear</button>
            <button class="btn btn-primary" name="guardar">Guardar</button>
        </div>
    </form>

    <div class="mt-3">
        <table id="sorteoTable" class="display" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombre Completo</th>
                    <th>Programa</th>
                    <th>Firma</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['filtrados'])) {
                    $i = 1;
                    foreach ($_SESSION['filtrados'] as $row) {
                        $nombre_completo = $row['paterno'] . ' ' . $row['materno'] . ' ' . $row['nombres'];
                        $firma = !empty($row['firma']) ? "<img src='{$row['firma']}' alt='Firma' width='100'>" : "";
                        
                        echo "<tr>
                                <td>$i</td>
                                <td>{$row['dni']}</td>
                                <td>$nombre_completo</td>
                                <td>{$row['programa']}</td>
                                <td>$firma</td>
                              </tr>";
                        
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    $('#programa').select2();
    $('#programa').on('change', function () {
        $('form').submit();
    });

    let programaTexto = $('#programa option:selected').text();
    if (programaTexto === 'Todos los programas') programaTexto = 'General';
    const now = new Date().toLocaleString();

    $('#sorteoTable').DataTable({
        dom: 'Bfrtip',
        paging: <?php echo isset($_POST['sortear']) ? 'false' : 'true'; ?>,
        buttons: [
            {
                extend: 'pdfHtml5',
                text: 'Exportar a PDF',
                title: '',
                orientation: 'portrait',
                pageSize: 'A4',
                customize: function (doc) {
                    doc.content.splice(0, 0, {
                        image: 'data:image/png;base64,<?php echo base64_encode(file_get_contents("assets/media/vracad_preview.png")); ?>',
                        alignment: 'center',
                        width: 50,
                        margin: [0, 0, 0, 5]
                    });
                    doc.content.splice(1, 0, {
                        text: 'VICERRECTORADO ACADÉMICO - UNA PUNO\nLista de Ganadores del Sorteo del programa: ' + programaTexto,
                        fontSize: 11,
                        alignment: 'center',
                        margin: [0, 0, 0, 8],
                        bold: true
                    });
                    doc.styles.tableHeader.fontSize = 8;
                    doc.styles.tableBodyEven.fontSize = 8;
                    doc.styles.tableBodyOdd.fontSize = 8;
                    doc.content[2].table.widths = ['5%', '15%', '20%', '20%', '30%'];
                    doc.content[2].table.body.forEach(row => {
                        row[4].text = '';
                        row.forEach(cell => {
                            cell.border = [true, true, true, true];
                            cell.margin = [3, 8, 3, 8];
                        });
                    });
                    doc.footer = function (currentPage, pageCount) {
                        return {
                            columns: [
                                { text: 'Generado: ' + now, alignment: 'left', margin: [40, 0, 0, 0] },
                                { text: 'Página ' + currentPage + ' de ' + pageCount, alignment: 'right', margin: [0, 0, 40, 0] }
                            ]
                        }
                    };
                },
                download: 'open',
                filename: 'Ganador ' + programaTexto
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
        }
    });


    
});
</script>



<?php include('includes/footer.php'); ?>
