<?php 

    $hostname = "172.80.80.24";
    $database = "ingresantes2025";
    $user = "michael";
    // $user = "vrcad";
    $password = "michael1234";
    //    $password = "#Mn5542hg";


    $conn = mysqli_connect($hostname, $user, $password, $database);


    if(!$conn){
        die( "Conection Failed". mysqli_connect_error());
    }

    /// echo "Conectado !";

    // mysqli_close($conn);

?>






