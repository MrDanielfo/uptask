<?php

    error_reporting(E_ALL ^ E_NOTICE);
    include 'inc/functions/sesiones.php'; 
    include 'inc/functions/funciones.php';
    include 'inc/templates/header.php'; 

    // Obtener id de la URL

    if(isset($_GET['id_proyecto'])) {

        $idProyecto = $_GET['id_proyecto'];

    }

?>

    <?php include  'inc/templates/barra.php';  ?>
    

    <div class="contenedor">
        
        <?php  include 'inc/templates/sidebar.php';  ?>

        <main class="contenido-principal">
            <?php $proyecto = obtenerNombreProyecto($idProyecto);

            if($proyecto) : ?>

            <?php foreach ($proyecto as $nombre) : ?>
            <h1>Nombre del Proyecto
                <span><?php  echo $nombre['nombre']; ?></span>
                <?php endforeach; ?>

            </h1>

            <form action="#" class="agregar-tarea" id="agregar-tarea">
                <div class="campo">
                    <label for="tarea">Tarea:</label>
                    <input type="text" placeholder="Nombre Tarea" class="nombre-tarea"> 
                </div>
                <div class="campo enviar">
                    <input type="hidden" id="proyecto-id" value="<?php echo $idProyecto; ?>">
                    <input type="submit" class="boton nueva-tarea" value="Agregar">
                </div>
            </form>
            
        <?php else: 


            echo "<h1>Selecciona un proyecto a la izquierda</h1>";


            endif; 
        ?>    

            <h2>Listado de tareas:</h2>

            <div class="listado-pendientes">
                <ul id="listado-tareas">
                    <?php $tareas = obtenerTareasId($idProyecto);

                    if ($tareas->num_rows > 0) : ?>

                        <?php foreach ($tareas as $tarea) : ?>
                            <li id="tarea-<?php echo $tarea['id'];  ?>" class="tarea">
                                <p><?php echo $tarea['nombre'];  ?></p>
                                <div class="acciones">
                                    <i class="far fa-check-circle <?php echo ($tarea['estado'] === "1") ? 'completo' : "" ?>"></i>
                                    <i class="fas fa-trash"></i>
                                </div>
                            </li>
                        <?php endforeach; ?>
                <?php else: 

                        echo "<li class='lista-vacia'>Aún no hay tareas asignadas a este proyecto</li>"; 

                    endif; 
                ?>
                </ul>
            </div>
        </main>
    </div><!--.contenedor-->

<?php
    include 'inc/templates/footer.php';
?>