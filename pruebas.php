<?php
$con = mysqli_connect("localhost", "root", "", "vracaduna");

// Verificar la conexión
if (mysqli_connect_errno()) {
  echo "Error al conectar a MySQL: " . mysqli_connect_error();
}

// Ejecutar la consulta
$query = "SELECT *
FROM (
    SELECT
        consul.suma AS mxc,
        gc.gc_id,
        dc.dc_id,
        c.*,
        g.grupo,
        gc.gc_turno,
        gc.gc_aula,
        gc.gc_alumnos,
        d.docente_id,
        d.doc_nombres,
        d.doc_paterno,
        d.doc_materno,
        d.renacyt,
        d.condicion,
        d.dedicacion,
        p.escuela AS proce,
        g.prog_id AS p,
        dc.obser,
        p.id_escu,
        p.prog_id AS progmos,
        c.prog_id AS idprogcurso,
        pro.escuela,
        COUNT(d.docente_id) OVER (PARTITION BY d.docente_id) AS contador
    FROM cursos c
    INNER JOIN grupo g ON g.curso_id = c.curso_id
    INNER JOIN grupo_curso gc ON g.grupo_id = gc.cursogc_id
    LEFT JOIN docente_curso dc ON gc.gc_id = dc.cursodc_id
    LEFT JOIN docentes d ON d.docente_id = dc.docente_id
    LEFT JOIN program p ON p.prog_id = d.prog_id
    INNER JOIN program pro ON pro.prog_id = c.prog_id
    LEFT JOIN (
        SELECT h.gch_id, COUNT(gch_id) AS suma
        FROM horario h
        RIGHT JOIN time t ON t.time_id = h.time_id
        GROUP BY gch_id
    ) AS consul ON consul.gch_id = gc.gc_id
) AS m
INNER JOIN (
    SELECT
        h.docente_id,
        SUM(h.curso_totalh) AS total_horas
    FROM (
        SELECT
            gc.gc_alumnos,
            d.docente_id,
            CASE
                WHEN gc.gc_alumnos < 15 AND c.curso_ciclo IN ('I', 'II', 'III', 'IV', 'V') THEN c.curso_totalh / 2
                WHEN c.curso_ciclo IN ('VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV') AND gc.gc_alumnos <= 7 THEN c.curso_totalh / 2
                ELSE c.curso_totalh
            END AS curso_totalh,
            c.curso_ciclo
        FROM docente_curso dc
        INNER JOIN docentes d ON dc.docente_id = d.docente_id
        INNER JOIN grupo_curso gc ON dc.cursodc_id = gc.gc_id
        INNER JOIN grupo g ON gc.cursogc_id = g.grupo_id
        INNER JOIN cursos c ON g.curso_id = c.curso_id
    ) AS h
    GROUP BY h.docente_id
) AS n ON m.docente_id = n.docente_id;";

$result = mysqli_query($con, $query) or die(mysqli_error($con));
?>

<!DOCTYPE html>
<html lang="es-ES">
<head>
  <meta charset="utf-8">
  <title>Tabla HTML con Datos y Rowspan</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }
  </style>

<button onclick="downloadPDF()">Download PDF</button>
<button onclick="downloadExcel()">Download Excel</button>

</head>
<body>
  <table>
    <tr>
      <th>ID Grupo Curso</th>
      <th>ID Docente Curso</th>
      <th>ID Docente</th>
      <th>Nombre del Docente</th>
      <th>Nombre del Docente</th>
    </tr>
    <?php
    $prevDocenteId = "";

    while ($row = mysqli_fetch_assoc($result)) {
      $gcId = $row['gc_id'];
      $dcId = $row['dc_id'];
      $docenteId = $row['docente_id'];
      $docNombres = $row['doc_nombres'];
      $contador = $row['contador'];

      echo "<tr>";
      echo "<td>$gcId</td>";
      echo "<td>$dcId</td>";
      echo "<td>$docenteId</td>";

      if ($docenteId !== $prevDocenteId) {
        echo "<td rowspan=\"$contador\">$docNombres</td>";
        $prevDocenteId = $docenteId;
      } else {
        // Aquí puedes manejar el caso cuando $docenteId es igual al anterior
      }

      echo "<td>$contador</td>";

      echo "</tr>";
    }
    ?>
  </table>
</body>
</html>
<script>
    function downloadPDF() {
        // You can place your PDF generation code here (using TCPDF)
        // ...
        // After generating the PDF, you can create a download link
        var pdfBlob = ...; // The generated PDF as a Blob
        var link = document.createElement('a');
        link.href = URL.createObjectURL(pdfBlob);
        link.download = 'report.pdf';
        link.click();
    }

    function downloadExcel() {
        // You can place your Excel export code here (using PHPExcel)
        // ...
        // After generating the Excel file, you can create a download link
        var link = document.createElement('a');
        link.href = 'report.xlsx'; // Provide the correct path to your Excel file
        link.download = 'report.xlsx';
        link.click();
    }
</script>