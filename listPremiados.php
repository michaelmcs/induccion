<?php include('includes/header.php') ?>
<?php include('db/conection.php') ?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>DataTables.js</title>
        <!-- Bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous" />
        <!-- DataTable -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
        <!-- Font Awesome -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
            integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/styles.css" />
    </head>
    <body>
        <div class="container my-4">
            <div class="container">
                <h4 class="text-center">Lista de Estudiantes Premiados</h4>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <table id="datatable_users" class="table table-striped">
                        <caption>
                            Asistentes
                        </caption>
                        <thead>
                            <tr class="table-dark">
                                <th class="centered">#</th>
                                <th class="centered">DNI</th>
                                <th class="centered">NOMBRES</th>
                                <th class="centered">APELLIDO PATERNO</th>
                                <th class="centered">APELLIDO MATERNO</th>
                                <th class="centered">ESTADO</th>
                                <th class="centered">OPCIONES</th>
                            </tr>
                        </thead>

  
                        <tbody id="tableBody_users">

                        <?php
       
      
       $query=mysqli_query($conn,"SELECT * FROM alumnos a 
       INNER JOIN ganadores g ON a.dni = g.dnig ")or die(mysqli_error($con));
         
         while($row=mysqli_fetch_array($query)){
           $id=$row['id'];
           $dni=$row['dni'];
           $nombre=$row['nombres'];
           $paterno=$row['paterno'];
           $materno=$row['materno'];
        
             ?>
               <?php 
 
                     ?>
                     <tr>
               <td><?php echo $id;?></td> 
               <td><?php echo $dni;?></td> 
               <td><?php echo $nombre;?></td>
               <td><?php echo $paterno;?></td>
               <td><?php echo $materno;?></td>
               </tr>

             
<?php }?> 


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Bootstrap-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- DataTable -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
        <!-- Custom JS -->
        <script src="assets/css/main.js"></script>
    </body>
</html>


<?php include('includes/footer.php') ?>