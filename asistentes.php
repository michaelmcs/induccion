<?php include('includes/header.php'); ?>
<?php include('db/conection.php'); ?>

<div class="container mt-4">

<div class="row mb-3">
    <div class="col-md-6">
        <h4 class="text-center text-uppercase">Lista de Asistentes a la Inducción 2025-I</h4>
    </div>
    <div class="col-md-6">
        <select id="programaFiltro" class="form-select select2">
            <option value="">Todos los Programas</option>
            <?php
            $programas = mysqli_query($conn, "SELECT DISTINCT p.programa 
                FROM alumnos a 
                INNER JOIN program p ON a.id_progariel = p.prog_id 
                ORDER BY p.programa ASC");
            while ($prog = mysqli_fetch_assoc($programas)) {
                echo "<option value=\"{$prog['programa']}\">{$prog['programa']}</option>";
            }
            ?>
        </select>
    </div>
</div>


  <table id="datatable_users" class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>N°</th>
        <th>DNI</th>
        <th>Nombre Completo</th>
        <th>Programa</th>
        <th>Hora</th>
        <th>Firma</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = mysqli_query($conn, "SELECT * FROM alumnos a
        INNER JOIN asistencia asis ON a.dni = asis.dni 
        INNER JOIN program p ON a.id_progariel = p.prog_id
        ORDER BY a.paterno, a.materno, a.nombres ASC");

      $i = 1;
      while($row = mysqli_fetch_array($query)) {
        $nombreCompleto = "{$row['paterno']} {$row['materno']} {$row['nombres']}";
        echo "<tr>";
        echo "<td>{$i}</td>";
        echo "<td>{$row['dni']}</td>";
        echo "<td>{$nombreCompleto}</td>";
        echo "<td>{$row['programa']}</td>";
        echo "<td>" . date('H:i:s', strtotime($row['fecharegistro'])) . "</td>";
        echo "<td></td>";
        echo "</tr>";
        $i++;
      }
      ?>
    </tbody>
  </table>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('.select2').select2();


  const table = $('#datatable_users').DataTable({
    dom: 'Bfrtip',
    order: [[2, 'asc']], // orden alfabético por Nombre Completo
    buttons: [{
      extend: 'pdfHtml5',
      text: 'Exportar a PDF',
      orientation: 'landscape',
      pageSize: 'A4',
      title: '',

      customize: function(doc) {
    doc.pageOrientation = 'portrait'; // orientación vertical

    // Logo
    doc.content.splice(0, 0, {
        alignment: 'center',
        image: 'data:image/png;base64,<?php echo base64_encode(file_get_contents("assets/media/vracad_preview.png")); ?>',
        width: 60,
        margin: [0, 0, 0, 10]
    });

    // Título
    doc.content.splice(1, 0, {
        text: 'VICERRECTORADO ACADÉMICO - UNA PUNO\nLista de Estudiantes Asistentes al Evento',
        alignment: 'center',
        fontSize: 13,
        bold: true,
        margin: [0, 0, 0, 15]
    });

    // Ajustes generales
    doc.defaultStyle.fontSize = 9;
    doc.styles.tableHeader.fontSize = 9;
    doc.styles.tableHeader.alignment = 'center';

    // Ajustar ancho: más espacio para la firma
    doc.content[2].table.widths = ['5%', '12%', '30%', '20%', '13%', '20%'];

    // Borde y margen a cada celda
    const body = doc.content[2].table.body;
    for (let i = 0; i < body.length; i++) {
        for (let j = 0; j < body[i].length; j++) {
            body[i][j].border = [true, true, true, true];
            body[i][j].margin = [2, 12, 2, 12];

            // Alineación especial
            if (j === 2) body[i][j].alignment = 'left'; // Nombre Completo
            else body[i][j].alignment = 'center';
        }
    }

    // Pie de página
    const now = new Date();
    const fechaHora = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
    doc.footer = function(currentPage, pageCount) {
        return {
            columns: [
                { text: 'Generado: ' + fechaHora, alignment: 'left', margin: [40, 0, 0, 0] },
                { text: 'Página ' + currentPage + ' de ' + pageCount, alignment: 'right', margin: [0, 0, 40, 0] }
            ]
        };
    };
}




    }],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
    }
  });

  // Filtro por programa
  $('#programaFiltro').on('change', function () {
    const selected = $(this).val();
    table.column(3).search(selected).draw();
  });
});
</script>

<?php include('includes/footer.php'); ?>
