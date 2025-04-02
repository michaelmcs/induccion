<?php include('includes/header.php') ?>
<?php include('db/conection.php') ?>


<div class="d-flex justify-conter-center align-items-center  bg-custom" style="min-height: calc(100vh - 60px);">

    <div class="container ">
        <div class="search_container px-5">
            <div class="container px-5">
                <h4 class="text-center">Registro GANA</h4>
                <br>
                <div class="container px-5">
                    <div class="container px-5">
                        <form class="row g-3" action="db/busqueda.php" method="GET">
                            <input class="form-control" type="text" min="1" max="8" id="dni" name="query" required autofocus placeholder="Buscar  DNI">
                            <input class="btn btn-primary" type="submit" value="Buscar">
                        </form>
                        <div class="img-center">
                        <img src="assets/media/vracad_preview.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    var input = document.getElementById('dni');
    input.addEventListener('input', function() {
        if (this.value.length > 8)
            this.value = this.value.slice(0, 8);
    })
</script>
<!-- 
<input class="input_hiden" type="text" id="codigo" placeholder="Enfoca este input y usa el lector"> -->

<?php include('includes/footer.php') ?>