
eventListeners();

function eventListeners() {

    document.querySelector('#formulario').addEventListener('submit', validarRegistro); 
}

function validarRegistro(e) {

    e.preventDefault(); 

    let usuario = document.querySelector('#usuario').value;
    let password = document.querySelector('#password').value;
    let tipo = document.querySelector('#tipo').value;

        if(usuario === '' || password === '') {
            swal({
                type: 'error',
                title: 'Hubo un error',
                text: 'Los campos no pueden estar vacíos'
            }); 

        } else {
            // ambos campos son correctos

            let datos = new FormData();
                datos.append('usuario', usuario);
                datos.append('password', password);
                datos.append('tipo', tipo); 

            // crear el llamado

            let xhr = new XMLHttpRequest();

            xhr.open('POST', 'inc/models/modelo-admin.php', true); 

            // retorno de datos
            
            xhr.onload = function() {
                if(this.status === 200) {
                    const respuesta =  JSON.parse(xhr.responseText);
                    console.log(respuesta);
                    if(respuesta.mensaje === 'correcto') {

                        if(respuesta.tipo === 'crear') {
                            swal({
                                title: 'Usuario Creado',
                                type: 'success',
                                text: 'El usuario se creó correctamente'
                            });
                        } else if(respuesta.tipo === 'login') {
                            swal({
                                title: 'Login Correcto',
                                type: 'success',
                                text: 'Presiona ok para ir al dashboard'
                            })
                            .then(resultado => {
                                if(resultado.value) {
                                    window.location.href = 'index.php';
                                }
                            })


                        }

                    } else {
                        swal({
                            type: 'error',
                            title: 'Hubo un error',
                            text: 'Hubo un error con la base de datos'
                        });
                    } // fin de mensaje correcto 

                }
            }

            xhr.send(datos); 

        }




}