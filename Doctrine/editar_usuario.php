<?php
require_once 'libreria.php';
session_start();
$file = 'usuarios.json';
$success = false;
$errorLogin = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"]);

    if (!empty($_SESSION['usuario'])) 
    {
        $usuario = $_SESSION['usuario'];
        $usuario->editarUsuario($email, $_POST["password"], $_POST["telephone"], $_POST["direccion"], $_POST["localidad"], $_POST["name"], $_POST["codigo_postal"], $_POST["tarjeta"], $_POST["mes_caducidad"], $_POST["year_caducidad"], $_POST["ccv"]);
        $success = true;
    }
}
?>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="editar_usuario.css">
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
                <div id="titulo"><h1>Editar Perfil</h1>
                Rellene los apartados que desee editar de su perfil 
                </div>
                <div class="barra2"></div>
                <div class="fila" id="loginContainer">
                    <div id="logindiv">
                        <form class="margin" id="update" method="post" action="#">
                            <h1>Nuevos Datos</h1>
                            <div class="alerta_exito">
                                Ha modificado el perfil con éxito.
                            </div>
                            <div class="alerta_errorLogin2">
                                Para acceder a esta característica debe iniciar sesión.
                            </div>
                            <div class="alerta_errorLogin">
                            <?php 
                                if ($success)
                                {
                                    echo "<script>$('.alerta_exito').show();</script>";
                                } 
                                else
                                {
                                    echo "<script>$('.alerta_exito').hide();</script>";
                                }
                                if (empty($_SESSION['usuario'])) 
                                {
                                    echo "<script>$('.alerta_errorLogin2').show();</script>";
                                    echo "<script>$('.boton').hide();</script>";
                                }
                            ?>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Email</div>
                                <div class="field"><input type="text" id="email" name="email"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Nombre Completo</div>
                                <div class="field"><input type="text" id="name" name="name"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Nueva Contraseña</div>
                                <div class="field"><input type="text" id="password" name="password"></input></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Teléfono</div>
                                <div class="field"><input type="tel" id="telephone" name="telephone"></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Dirección</div>
                                <div class="field"><input type="text" id="direccion" name="direccion"></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Localidad</div>
                                <div class="field"><input type="text" id="localidad" name="localidad"></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Código Postal</div>
                                <div class="field"><input type="text" id="codigo_postal" name="codigo_postal"></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Número de Tarjeta</div>
                                <div class="field"><input type="number" id="tarjeta" name="tarjeta"></div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">Fecha de Caducidad</div>
                                <div class="field">
                                    <input type="number" id="mes_caducidad" name="mes_caducidad" min="1" max="12" placeholder="Mes">
                                    <input type="number" id="año_caducidad" name="year_caducidad" min="2025" placeholder="Año">
                                </div>
                            </div>
                            <div class="filaLogin">
                                <div class="label">CCV</div>
                                <div class="field"><input type="number" id="ccv" name="ccv"></div>
                            </div>
                            <div class="filaLogin">
                                <button type="submit" class="boton">Editar Perfil</button>
                            </div>
                            <script>
                                $(document).ready(function() {
                                   $('#update').submit(function(event) {
                       
                                        var error = false;
                                        var listaErrores = $('<ul>')
                                        var regExpEmail = /^[a-zA-Z0-9]+@[a-zA-Z0-9].+[a-z]$/;
                        
                                        $('.alerta_errorLogin').html('<p><b>Errores</b></p>').show();
                        
                                        if( $('#email').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse el nuevo email").appendTo(listaErrores);
                                        }
                                        else
                                        {
                                            if( !regExpEmail.test($('#email').val())) 
                                            {
                                                error = true;
                                                $('<li>').html("El email introducido no es válido").appendTo(listaErrores);
                                            }
                                        }
                                        if( $('#name').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse el nombre").appendTo(listaErrores);
                                        }
                                        if( $('#telephone').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse el telefono").appendTo(listaErrores);
                                        }
                                        if( $('#direccion').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse la direccion").appendTo(listaErrores);
                                        }
                                        if( $('#localidad').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse la localidad").appendTo(listaErrores);
                                        }
                                        if( $('#codigo_postal').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse el código postal").appendTo(listaErrores);
                                        }
                                        if( $('#tarjeta').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse la tarjeta bancaria").appendTo(listaErrores);
                                        }
                                        if( $('#ccv').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Ha de introducirse el ccv").appendTo(listaErrores);
                                        }
                                        if( $('#password').val() == "") 
                                        {
                                            error = true;
                                            $('<li>').html("Has de introducir la nueva contraseña").appendTo(listaErrores);
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
</html>