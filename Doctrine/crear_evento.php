<html lang="es">

<?php
    require_once 'eventos_controladora.php';
    session_start();
    $controladora = new eventos_controladora();
    $success = false;
    $errores = [];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $resultado = $controladora->procesarCreacion($_POST);
        $errores = $resultado['errores'];
        $success = $resultado['success'];
    }
?>

    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="crear_evento.css">
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
            <section class="main promotor">
                <div id="titulo"><h1>Creación de evento</h1></div>
                <div class="barra2"></div>
                <div class="margin">
                <div id="logindiv">
                    <form class="margin" id="update" method="post" action="#">
                        <h1>Datos Evento</h1>
                        <div class="alerta_errorLogin">
                            <?php 
                                if (!empty($errores))
                                {
                                    echo "<p><b>Errores</b></p>";
                                    foreach ($errores as $error) {
                                        echo "<ul><li>" . htmlspecialchars($error) . "</li></ul>";
                                    }
                                    $success = false;
                                }
                            ?>
                        </div>
                        <div class="alerta_exito">El evento se ha creado con éxito.</div>
                        <div class="filaLogin">
                            <div class="label">Nombre</div>
                            <div class="field"><input type="text" id="nombre" name="nombre"></input></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Ubicación</div>
                            <div class="field"><input type="text" id="ubicacion" name="ubicacion"></input></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Descripción</div>
                            <div class="field"><textarea id="descripcion" name="descripcion"></textarea></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Precio por entrada</div>
                            <div class="field"><input type="text" id="precio" name="precio"></input></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Número de plazas</div>
                            <div class="field"><input type="text" id="numEntrada" name="numEntrada"></input></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Tipo </div>
                            <div class="field"><input type="text" id="tipo" name="tipo"></textarea></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Fecha Inicio</div>
                            <div class="field"><input type="date" id="fInicio" name="fInicio"></input></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Fecha Fin</div>
                            <div class="field"><input type="date" id="fFin" name="fFin"></input></div>
                        </div>
                        <div class="filaLogin">
                            <button id="updateSend" class="boton">Crear</button>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#update').submit(function(event) {
                    
                                    var error = false;
                                    var listaErrores = $('<ul>')
                                    $('.alerta_errorLogin').html('<p><b>Errores</b></p>').show();
                    
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
            <section class="none">
                <div class="noneContenedor">
                    <div class="alerta_Login">
                        <h1>Debe iniciar sesión como promotor previo a crear eventos</h1>
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
                    <a class="ignoreLink" href="ticket.php"><li>Eventos Inscritos</li></a>
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
    <?php 
        if(!empty($_SESSION['usuario']))
        {
            $tempUser = $_SESSION['usuario'];
            if($tempUser->getRol() === 'promotor')
            {
                echo "<script>$('.none').hide();</script>";
            }
            else
            {
                echo "<script>$('.promotor').hide();</script>";
            }
            if ($success)
            {
                echo "<script>$('.alerta_exito').show();</script>";
                echo "<script>$('.alerta_errorLogin').hide();</script>";
            } 
            else
            {
                echo "<script>$('.alerta_exito').hide();</script>";
                echo "<script>$('.alerta_errorLogin').show();</script>";
            }
        }
        else{
            echo "<script>$('.promotor').hide();</script>";
        }
    ?>
</html>