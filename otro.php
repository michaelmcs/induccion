 
 <?php session_start();
if(empty($_SESSION['id'])):
header('Location:../index.php');
endif;

// if($_POST)
// {
include('../dist/includes/dbcon.php');

	$dni = $_POST['dni'];			
	$codigo = $_POST['docente_code'];		
	$nombres = $_POST['nombres'];
	$ape_pa= $_POST['ape_pa'];		
	$ape_ma= $_POST['ape_ma'];
    $genero= $_POST['genero'];
	
	$categoria= $_POST['categoria'];
    $condicion= $_POST['condicion'];
    $hsemanales= $_POST['hsemanales'];
    
	$dedicacion= $_POST['dedicacion'];
    $lectivas= $_POST['lectivas'];
    $grado= $_POST['grado'];
	$correo= $_POST['correo'];
	$celular= $_POST['celular'];
    $funcion= $_POST['funcion'];
    $renacyt= $_POST['renacyt'];


$a = $_SESSION['id'];

 if($a==100){

        $prog= $_POST['programa'];

        	// $query=mysqli_query($con,"select * from docentes where doc_dni='$dni'")or die(mysqli_error($con));
            
    $query=mysqli_query($con,"select * from docentes where doc_dni='$dni' AND prog_id = '$prog'")or die(mysqli_error($con));
    
    $count=mysqli_num_rows($query);		
    if ($count>0)
        {
            echo "<script type='text/javascript'>alert('Ya Existe el Docente!');</script>";	
        echo "<script>document.location='docentes.php'</script>";  
        }
    else
    {	
        mysqli_query($con,"INSERT INTO docentes(doc_dni,doc_codigo,doc_nombres,doc_paterno,doc_materno,doc_sexo,condicion,categoria,dedicacion,hsemanales,lectivas,grado,celular,correo,funcion,renacyt,prog_id) 
        VALUES('$dni',
        '$codigo',
        '$nombres',
        '$ape_pa',
        '$ape_ma',
        '$genero',
        '$condicion',
        '$categoria',
        '$dedicacion',
        '$hsemanales',
        '$lectivas',
        '$grado', 
        '$celular',
        '$correo',
        '$funcion', 
        '$renacyt', 
        '$prog ')")or die(mysqli_error($con));
        
        echo "<script type='text/javascript'>alert('Docente Agregado Con Exito!');</script>";	
        echo "<script>document.location='docentes.php'</script>";  
    }

 }else{
    $prog = $_SESSION['id'];

    	// $query=mysqli_query($con,"select * from docentes where doc_dni='$dni'")or die(mysqli_error($con));
        $query=mysqli_query($con,"select * from docentes where doc_dni='$dni' AND prog_id = '$prog'")or die(mysqli_error($con));
    
        $count=mysqli_num_rows($query);		
        if ($count>0)
            {
                echo "<script type='text/javascript'>alert('Ya Existe el Docente!');</script>";	
            echo "<script>document.location='docentes.php'</script>";  
            }
        else
        {	
            mysqli_query($con,"INSERT INTO docentes(doc_dni,doc_codigo,doc_nombres,doc_paterno,doc_materno,doc_sexo,condicion,categoria,dedicacion,hsemanales,lectivas,grado,celular,correo,funcion,renacyt,prog_id) 
            VALUES('$dni',
            '$codigo',
            '$nombres',
            '$ape_pa',
            '$ape_ma',
            '$genero',
            '$condicion',
            '$categoria',
            '$dedicacion',
            '$hsemanales',
            '$lectivas',
            '$grado', 
            '$celular',
            '$correo',
            '$funcion', 
            '$renacyt', 
            '$prog ')")or die(mysqli_error($con));
            
            echo "<script type='text/javascript'>alert('Docente Agregado Con Exito!');</script>";	
            echo "<script>document.location='docentes.php'</script>";  
        }
 }



// }					  
	
?>