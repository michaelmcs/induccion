<?php 

    $hostname = "localhost";
    $database = "ingresantes2025";
    $user = "root";
    // $user = "vrcad";
    $password = "";
    //    $password = "#Mn5542hg";


    $conn = mysqli_connect($hostname, $user, $password, $database);


    if(!$conn){
        die( "Conection Failed". mysqli_connect_error());
    }

    /// echo "Conectado !";

    // mysqli_close($conn);

?>






