
eventListeners();

const listaProyectos = document.querySelector('#proyectos'); 


function eventListeners()  {

    // nuevo Proyecto

    document.querySelector('.crear-proyecto a').addEventListener('click', nuevoProyecto);

    // nueva Tarea 

    document.querySelector('.nueva-tarea').addEventListener('click', agregarTarea); 

    // completar Tarea 

    document.querySelector('.listado-pendientes').addEventListener('click', accionesTareas); 

}

function nuevoProyecto(e) {

    e.preventDefault();

    // Crear el input para el nombre del nuevo proyecto

    let nuevoProyecto = document.createElement('li');
    nuevoProyecto.innerHTML = '<input type="text" id="nuevo-proyecto">'; 
    listaProyectos.appendChild(nuevoProyecto); 

    // Seleccionar el ID con el nuevoProyecto

    let inputNuevoProyecto = document.querySelector('#nuevo-proyecto'); 

    // presionar enter crear el proyecto

    inputNuevoProyecto.addEventListener('keypress', function(e) {
        console.log(e); 
        let tecla = e.which || e.keyCode
        if(tecla === 13) {
            guardarProyectoDB(inputNuevoProyecto.value); 
            listaProyectos.removeChild(nuevoProyecto); 
        }
    })

}

function guardarProyectoDB(proyecto) {

    // CREAR LLAMADO

    let xhr = new XMLHttpRequest();
    let url = 'inc/models/modelo-proyecto.php';

    // formdata

    let datos = new FormData();
        datos.append('proyecto', proyecto);
        datos.append('tipo', 'crear');  

    xhr.open('POST', url, true);

    xhr.onload = function() {

        if(this.status === 200) {

            const respuesta = JSON.parse(xhr.responseText);
            //console.log(respuesta); 
            let proyecto = respuesta.proyecto;
            let id = respuesta.id;
            let tipo = respuesta.tipo; 
            let mensaje = respuesta.mensaje; 

            if(mensaje === 'correcto') {

                if(tipo === 'crear') {

                    let nuevoProyecto = document.createElement('li');
                    nuevoProyecto.innerHTML = `
                                                <a href="index.php?id_proyecto=${id}" id="proyecto-${id}">
                                                    ${proyecto}
                                                </a> 
                                            `;

                    listaProyectos.appendChild(nuevoProyecto);
                    swal({
                        title: 'Proyecto Creado',
                        type: 'success',
                        text: `El proyecto: ${proyecto} fue creado exitosamente`
                    })
                    .then(resultado => {
                        // redireccionar a url del proyecto
                        if(resultado.value) {
                            window.location.href = `index.php?id_proyecto=${id}`; 
                        }
                    })

                } else {
                    // se actualizó o eliminó proyecto
                }

            } else {
                swal({
                    type: 'error',
                    title: 'Hubo un error',
                    text: `${mensaje}`
                }); 
            }


        }

    }

    xhr.send(datos); 
}

function agregarTarea(e) {

    e.preventDefault(); 

    let nombreTarea = document.querySelector('.nombre-tarea').value; 
    let idProyecto = document.querySelector('#proyecto-id').value;

    if(nombreTarea === '') {

        swal({
            type: 'error',
            title: 'Campo Vacío',
            text: 'Debes escribir el nombre de la tarea'
        }); 

    } else {

        let xhr = new XMLHttpRequest();
        let url = 'inc/models/modelo-tarea.php';

        // formdata

        let datos = new FormData();
        datos.append('nombre', nombreTarea);
        datos.append('id_proyecto', idProyecto);
        datos.append('tipo', 'crear'); 

        xhr.open('POST', url, true);

        xhr.onload = function () {

            if (this.status === 200) {

                const respuesta = JSON.parse(xhr.responseText);
                //console.log(respuesta); 
                let mensaje = respuesta.mensaje;
                let tarea = respuesta.tarea;
                let tipo = respuesta.tipo;
                let id = respuesta.id;
                let proyecto_id = respuesta.id_proyecto; 

                if(mensaje === 'correcto') {
                    // se agregó correctamente 
                    if(tipo === 'crear') {

                        swal({
                            title: 'Tarea Creada',
                            type: 'success',
                            text: `La tarea: ${tarea} fue creada exitosamente`
                        });

                        let listaVacia = document.querySelectorAll('.lista-vacia');
                        if(listaVacia.length > 0) {
                            document.querySelector('.lista-vacia').remove();
                        }

                        // Construir el template
                        let nuevaTarea = document.createElement('li'); 
                        nuevaTarea.id = `tarea-${id}`;
                        nuevaTarea.classList.add('tarea');
                        // insertar en el HTML
                        nuevaTarea.innerHTML = `
                            <p>${tarea}</p>
                            <div class="acciones">
                                <i class="far fa-check-circle"></i>
                                <i class="fas fa-trash"></i>
                            </div>
                        `;

                        // insertar en el listado de tareas
                        let listadoTareas = document.querySelector('#listado-tareas');
                        listadoTareas.appendChild(nuevaTarea);

                        document.querySelector('.agregar-tarea').reset();

                    } else {
                        // se edita
                    }

                } else {
                    swal({
                        type: 'error',
                        title: 'Hubo un error',
                        text: 'Hubo un error en la base de datos'
                    }); 
                }

            }

        }

        xhr.send(datos); 
    }
}

// Cambia el estado de tareas o las elimina

function accionesTareas(e) {

    e.preventDefault(); 
    //console.log(e.target);
    if(e.target.classList.contains('fa-check-circle')) {

        if(e.target.classList.contains('completo')) {
            e.target.classList.remove('completo'); 
            // ajax
            cambiarEstadoTarea(e.target, 0);
        } else {
            e.target.classList.add('completo'); 
            cambiarEstadoTarea(e.target, 1);
        }
            
    } 

    if(e.target.classList.contains('fa-trash')) {

        swal({
            title: '¿Seguro que quieres borrar la tarea?',
            text: "Esta acción no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, quiero borrar la tarea!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            let eliminarTarea = e.target.parentElement.parentElement; 
            let idEliminar = eliminarTarea.id.split('-');
            // borrar de la base de datos
            eliminarTareaDB(idEliminar[1]);
            
            // borrar del HTML
            eliminarTarea.remove();
            if (result.value) {
                swal(
                    'Eliminada!',
                    'La tarea ha sido eliminada',
                    'success'
                )
            }
        })
    } 

}

// completa o descompleta Tarea 

function cambiarEstadoTarea(tarea, estado) {

    let idTarea = tarea.parentElement.parentElement.id.split('-'); 
    // otro método es split('-')
    // después se pone idTarea[1]; 
    
    let estadoTarea = estado; 

    console.log(idTarea, estadoTarea); 

    let xhr = new XMLHttpRequest();
    let url = 'inc/models/modelo-tarea.php';

    let datos = new FormData();

    datos.append('idTarea', idTarea[1]); 
    datos.append('estado', estadoTarea);
    datos.append('tipo', 'cambiar'); 

    xhr.open('POST', url, true);

    xhr.onload = function () {

        if (this.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if(respuesta.mensaje === 'correcto') {
                swal({
                    title: 'Tarea Actualizada',
                    type: 'success',
                    text: `La tarea fua actualizada`
                });
            } 
        }
    }

    xhr.send(datos); 

}

function eliminarTareaDB(idTarea) {

    let idEliminar = idTarea

    let xhr = new XMLHttpRequest();
    let url = 'inc/models/modelo-tarea.php';

    let datos = new FormData();

    datos.append('idEliminar', idEliminar);
    datos.append('tipo', 'eliminar');

    xhr.open('POST', url, true);

    xhr.onload = function () {

        if (this.status === 200) {
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.mensaje === 'correcto') {
                console.log(respuesta.mensaje);
                // Comprobar que haya tareas

                let tareasRestantes = document.querySelectorAll('li.tarea');
                console.log(tareasRestantes)

                if(tareasRestantes.length === 0) {
                    let sinLista = document.createElement('li');
                    sinLista.classList.add('lista-vacia');
                    sinLista.innerText = "Aún no hay tareas asignadas a este proyecto";
                    document.querySelector('#listado-tareas').appendChild(sinLista); 
                }
            }
        }
    }

    xhr.send(datos); 

}