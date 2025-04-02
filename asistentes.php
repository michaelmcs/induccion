<?php include('includes/header.php') ?>
<?php include('db/conection.php') ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>DataTables.js</title>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
        <!-- DataTables Buttons -->
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css" />
    </head>
    <body>
        <div class="container my-4">
            <div class="container">
                <h3 class="text-center">Lista de Asistentes a la Inducción de Ingresantes 2024-II</h3>
            </div>
            <div class="row">
                <div class="col-12">
                    <table id="datatable_users" class="table table-striped">
                        <thead>
                            <tr class="table-dark">
                                <th>#</th>
                                <th>DNI</th>
                                <th>Nombres</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Programa</th>
                                <th>Firma</th> <!-- Columna adicional para la firma -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $query = mysqli_query($conn, "SELECT * FROM alumnos a
                                INNER JOIN asistencia asis ON a.dni = asis.dni 
                                INNER JOIN program p ON a.id_progariel = p.prog_id
                                ORDER BY a.id DESC")  
                                or die("Error en la consulta: " . mysqli_error($conn));
                            
                            while($row = mysqli_fetch_array($query)){
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['dni'] . "</td>";
                                echo "<td>" . $row['nombres'] . "</td>";
                                echo "<td>" . $row['paterno'] . "</td>";
                                echo "<td>" . $row['materno'] . "</td>";
                                echo "<td>" . $row['programa'] . "</td>";
                                echo "<td></td>"; // Campo para la firma
                                echo "</tr>";
                            }
                        ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
        <!-- DataTables Buttons -->
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <!-- DataTables Initialization with PDF button -->
        <script>
            $(document).ready(function () {
                $('#datatable_users').DataTable({
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
                    },
                    "pageLength": 10, // Número de filas por defecto
                    "lengthMenu": [10, 50, 100, 500, 1000], // Opciones de filas por página
                    "order": [[0, "desc"]], // Ordenar por la primera columna en forma descendente
                    "dom": 'Bfrtilp', // Elementos del DOM: Botones, filtro, tabla, y selector de longitud al final
                    "buttons": [
                        {
                            extend: 'pdfHtml5',
                            text: 'Exportar a PDF',
                            title: 'Lista de Estudiantes Asistentes al Evento',
                            orientation: 'landscape', // Orientación horizontal
                            pageSize: 'A4', // Tamaño de la página
                            customize: function (doc) {
                                // Ajusta el ancho de las columnas en el PDF
                                doc.content[1].table.widths = ['5%', '10%', '20%', '15%', '15%', '20%', '15%'];
                                
                                // Disminuir tamaño de la fuente para ajustar contenido
                                doc.styles.tableHeader.fontSize = 9;
                                doc.styles.tableBodyEven.fontSize = 8;
                                doc.styles.tableBodyOdd.fontSize = 8;
                                
                                // Establece márgenes y otros estilos
                                doc.styles.tableHeader.alignment = 'center';
                                doc.styles.tableBodyEven.alignment = 'center';
                                doc.styles.tableBodyOdd.alignment = 'center';

                                // Ajuste de margen para la tabla
                                doc.content[1].margin = [10, 0, 10, 0];
                                
                                // Mover la línea de firma a la parte inferior de la celda
                                var tableBody = doc.content[1].table.body;
                                for (var i = 1; i < tableBody.length; i++) {
                                    tableBody[i][6].text = "____________________________";
                                    tableBody[i][6].margin = [0, 15, 0, 0]; // Ajusta la línea de firma hacia abajo
                                }
                            }
                        }
                    ]
                });
            });
        </script>
    </body>
</html>

<?php include('includes/footer.php') ?>
