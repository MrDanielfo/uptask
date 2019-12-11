    
    
    <script src="js/sweetalert2.all.min.js"></script>
    <?php
        $actual = obtenerPaginaActual();

        if($actual === 'crear-cuenta' || $actual === 'login') { ?>

            <script src="js/formulario.js"></script>

<?php } else {  ?>

            <script src="js/scripts.js"></script>

<?php }

    ?>


</body>
</html>