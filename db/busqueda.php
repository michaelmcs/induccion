<?php include("conection.php") ?>

<?php

if (isset($_GET["query"])) {
    $dnibusqueda = $_GET["query"];
    $estado  = '1';

    // Realizar la consulta en la base de datos
    // $sql = "SELECT * FROM alumnos WHERE dni LIKE '%$dnibusqueda%'";

    $sql = "SELECT * FROM alumnos  a INNER JOIN program p ON a.id_progariel = p.prog_id WHERE dni = $dnibusqueda ";

    $result = mysqli_query($conn, $sql); ?>



    <!DOCTYPE html>
    <html>
    <head>
<?php
    // Mostrar los resultados
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            echo "<div class='alert'>"; // Agrega el recuadro verde
            echo "<p style='font-size: 50px;  text-align: center;'>HOJA: <span style='font-weight: bold;'>" . $row["mesa"] . "</span></p>"; // Ajusta el tamaño del texto
            echo "<p style='font-size: 50px;  text-align: center;'>Número ORDEN: <span style='font-weight: bold;'>" . $row["id_firma"] . "</span></p>"; // Ajusta el tamaño del texto
            echo "<p style='font-size: 30px; text-align: center;'>" . $row["dni"] . "</p>";
            echo "<p style='font-size: 35px; text-align: center;'>" . $row["paterno"] . "</p>";
            echo "<p style='font-size: 35px; text-align: center;'>" . $row["materno"] . "</p>";
            echo "<p style='font-size: 35px; text-align: center;'>" . $row["nombres"] . "</p>";
            echo "<p style='font-size: 30px; text-align: center;'>" . $row["area"] . "</p>";
            echo "<p style='font-size: 30px; text-align: center;'>" . $row["programa"] . "</p>";
            echo "</div>"; // Cierra el recuadro verde
            
            
        }

                 $query=mysqli_query($conn,"SELECT * FROM asistencia WHERE dni = '$dnibusqueda' ")or die(mysqli_error($con));
                 $count=mysqli_num_rows($query);		
                        if ($count>0)
                        { ?>
                            <style>
                                .alert {
                                    background-color: yellow;
                                    color: black;
                                    padding: 10px;
                                    position: fixed;
                                    top: 0;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    opacity: 1;
                                    transition: opacity 0.5s ease-in-out;
                                }
                                .hidden {
                                    opacity: 0;
                                }
                            </style>
                        </head>
                        <body>
                            <div id="alertBox" class="alert">Estudiante ya registrado</div>

                            <script type="text/javascript">
                                setTimeout(function() {
                                    var alertBox = document.getElementById('alertBox');
                                    alertBox.classList.add('hidden');
                                }, 5000); // 5000 milliseconds = 5 seconds
                            </script>
                        </body>
                        </html>
                    <?php } 
        else{
        {
        mysqli_query($conn,"INSERT INTO asistencia(dni,estado,fecharegistro)
            VALUES('$dnibusqueda','$estado',CURRENT_TIMESTAMP())")or die(mysqli_error($conn));
        }			
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    .alert {
                        background-color: green;
                        color: white;
                        padding: 10px;
                        position: fixed;
                        top: 0;
                        left: 50%;
                        transform: translateX(-50%);
                        opacity: 1;
                        transition: opacity 0.5s ease-in-out;
                    }
                    .hidden {
                        opacity: 0;
                    }
                </style>
            </head>
            <body>
                <div id="alertBox" class="alert">ASISTENCIA REGISTRADO</div>

                <!-- <script type="text/javascript">
                    setTimeout(function() {
                        var alertBox = document.getElementById('alertBox');
                        alertBox.classList.add('hidden');
                    }, 5000); // 5000 milliseconds = 5 seconds
                </script> -->

            </body>
            </html>
        <?php 
            

        }   
            

        header("Refresh: 1.5; URL=../index.php");

    } else {

        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                .alert {
                    background-color: RED;
                    color: white;
                    padding: 10px;
                    position: fixed;
                    top: 0;
                    left: 50%;
                    transform: translateX(-50%);
                    opacity: 1;
                    transition: opacity 0.5s ease-in-out;
                }
                .hidden {
                    opacity: 0;
                }
            </style>
        </head>
        <body>
            <div id="alertBox" class="alert">NO SE ENCONTRO DNI </div>

            <script type="text/javascript">
                setTimeout(function() {
                    var alertBox = document.getElementById('alertBox');
                    alertBox.classList.add('hidden');
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
        </body>
        </html>
    <?php 
    header("Refresh: 0.8; URL=../index.php");
            
    }
} else {
    echo "Ingrese un término de búsqueda.";
}

?>
