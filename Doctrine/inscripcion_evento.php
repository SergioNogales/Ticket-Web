<html lang="es">
<?php
    require_once 'reserva_controladora.php';
    session_start();
    $controladora = new reserva_controladora();
    $success = false;
    $errores = [];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $resultado = $controladora->procesarReservaEvento($_POST);
        $errores = $resultado['errores'];
        $success = $resultado['success'];
    }
?>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="inscripcion_evento.css">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    </head>
    <body>
        <header>
            <div class="navegacion fila azul">
                <div class="fila" id="izquierda">
                    <div>               
                        <a class="ignoreLink" href="landing_page.php">TusEventos.com </a>
                    </div> 
                    <div class="fila" id="izquierda2">
                        <div><a class="ignoreLink" href="listado_eventos.php">Conciertos</a></div>
                        <div><a class="ignoreLink" href="listado_eventos.php">Deportes</a></div>
                        <div><a class="ignoreLink" href="listado_eventos.php">Teatro</a></div>
                        <div><a class="ignoreLink" href="listado_eventos.php">Festivales</a></div>
                        <div><a class="ignoreLink" href="solicitudes.php">Solicitudes de Rol</a></div>
                    </div>
                </div>
                <div id="derecha">
                    <input type="text">
                    <div>
                        <img src="icono.png"/>
                        <a class="ignoreLink" href="register.php">Iniciar Sesión / Registrarse</a>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <section class="main">
                <div id="titulo"><h1>Inscripción a evento</h1></div>
                <div class="barra2"></div>
                <div class="fila" id="loginContainer">
                    <div id="logindiv">
                        <form class="margin" id="update" method="post" action="#">
                            <h1>Datos adicionales</h1>
                            <div class="alerta_errorLogin"></div>
                            <div class="filaLogin">
                                <div class="label">Nombre completo del asistente</div>
                                <div class="field"><input type="text" id="name" name="name"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Edad</div>
                                <div class="field"><input type="text" id="edad" name="edad"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">DNI</div>
                                <div class="field"><input type="text" id="dni" name="dni"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Alergias (especificar cuales)</div>
                                <div class="field"><input type="text" id="alergia" name="alergia"></input></div>
                            </div>
                            <div class="filaLogin">
                                <button id="updateSend" class="boton">Inscribirse</button>
                            </div>
                            <script>
                                $(document).ready(function() {
                                   $('#update').submit(function(event) 
                                   {
                                       var error = false;
                                       var listaErrores = $('<ul>')
                                       var numero = /^[0-9]+$/;
                                       $('.alerta_errorLogin').html('<p><b>Errores</b></p>').show();
                       
                                        if( $('#name').val() == "") 
                                        {
                                           error = true;
                                           $('<li>').html("Ha de rellenarse el campo del nombre").appendTo(listaErrores);
                                        }
                                        if( $('#edad').val() == "") 
                                        {
                                           error = true;
                                           $('<li>').html("Ha de rellenarse el campo de edad").appendTo(listaErrores);
                                        }
                                        if(!numero.test($('#edad').val())) 
                                        {
                                           error = true;
                                           $('<li>').html("La edad debe ser un número").appendTo(listaErrores);
                                        }
                                        if( $('#edad').val() < 3) 
                                        {
                                           error = true;
                                           $('<li>').html("Los asistentes deben tener una edad mínima de 3 años").appendTo(listaErrores);
                                        }
                                        if( $('#DNI').val() == "") 
                                        {
                                           error = true;
                                           $('<li>').html("Ha de rellenarse el campo de DNI").appendTo(listaErrores);
                                        }
                       
                                       if( error ) 
                                       {  
                                           var alertas = $('.alerta_errorLogin');
                                           listaErrores.appendTo(alertas);
                                           alertas.show();
                                           event.preventDefault();
                                       }
                                   });
                                });
                           </script>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <footer>
            <div class="footer negro fila" id="apartadoLegal">
                <div id="contacto columna">
                    <h1>Redes Sociales</h1>
                    <div class="fila" id="redesSociales">
                        <div><a class="ignoreLink" href="https://twitter.com"><img src="assets/twitter.png">Twitter</a></div>
                        <div><a class="ignoreLink" href="https://instagram.com"><img src="assets/instagram.png">Instagram</a></div>
                        <div><a class="ignoreLink" href="https://facebook.com"><img src="assets/facebook.png">Facebook</a></div>
                    </div>
                    <h1>Contáctanos</h1>
                    <div class="columna" id="contactoCorreo">
                        <div>Correo electrónico: tuseventos@gmail.com</div>
                        <div>Teléfono: 999 999 999</div>
                    </div>
                </div>
                <div class="barra"></div>
                <div class="fila" id="indices">
                    <div>
                        <h1>Usuario</h1>
                        <li><a class="ignoreLink" href="login.php">Iniciar sesión</a></li>
                        <a class="ignoreLink" href="editar_usuario.php"><li>Editar perfil</li></a>
                        <a class="ignoreLink" href="#"><li>Eventos favoritos</li></a>
                        <a class="ignoreLink" href="listado_eventos.php"><li>Eventos Inscritos</li></a>
                    </div>
                    <div>
                        <h1>Promotor</h1>
                        <a class="ignoreLink" href="crear_evento.php"><li>Crear Eventos</li></a>
                        <a class="ignoreLink" href="editar_evento.php"><li>Editar Eventos</li></a>
                        <a class="ignoreLink" href="listado_eventos.php"><li>Listado de Eventos</li></a>
                    </div>
                    <div>
                        <a class="ignoreLink" href="landing_page.php"><h1>TusEventos.com</h1></a>
                        <a class="ignoreLink" href="#"><li>Política de privacidad</li></a>
                        <a class="ignoreLink" href="#"><li>Política de reembolsos</li></a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>