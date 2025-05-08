<html lang="es">

<?php
require_once 'libreria.php';
session_start();
$errorLogin = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $usuarios = getUsuarios();
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if(empty($email))
    {
        $errorLogin = "El campo email está vacio.";
    }
    if($password === '')
    {
        $errorLogin = "El campo contraseña está vacio.";
    }

    $usuarioEncontrado = false;
    foreach ($usuarios as $usuario) 
    {
        if ($usuario->email === $email)
        {
            $usuarioEncontrado = true;
            if ($password === $usuario->password) 
            {
                $_SESSION["usuario"] = $usuario;
                $success = true;
            } 
            else 
            {
                $errorLogin = "Contraseña incorrecta.";
                break;
            }
        }
    }
    
    if (!$usuarioEncontrado) 
    {
        $errorLogin = "Usuario no encontrado.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sergio Nogales Sanz">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
    <header>
        <div class="navegacion fila azul">
            <div class="fila" id="izquierda">
                <div><a class="ignoreLink" href="landing_page.php">TusEventos.com</a></div>
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
                    <a class="ignoreLink" href="register.php">Registrarse</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="main">
            <div id="titulo"><h1>Identifícate</h1></div>
            <div class="barra2"></div>
            <div class="fila" id="loginContainer">
                <div id="logindiv">
                    <form class="margin" id="login" method="post" action="login.php">
                        <h1>Iniciar Sesión</h1>
                        <div class="alerta_exito"> 
                            El inicio de sesión se completó con éxito.
                        </div>
                        <div class="alerta_errorLogin"> 
                            <?php 
                                if (!empty($errorLogin))
                                {
                                    echo "<p><b>Errores</b></p>";
                                    echo "<ul><li>" . htmlspecialchars($errorLogin) . "</li></ul>";
                                    $success = false;
                                }
                                if ($success)
                                {
                                    echo "<script>$('.alerta_exito').show();</script>";
                                } 
                                else
                                {
                                    echo "<script>$('.alerta_exito').hide();</script>";
                                }
                            ?>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Email</div>
                            <div class="field"><input type="text" id="emailLogin" name="email"></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Contraseña</div>
                            <div class="field"><input type="password" id="passwordLogin" name="password"></div>
                        </div>
                        <div class="filaLogin">
                            <button type="submit" class="boton">Iniciar Sesión</button>
                        </div>
                        <script>
                                $(document).ready(function() {
                                   $('#login').submit(function(event) 
                                   {
                                        var error = false;
                                        var listaErrores = $('<ul>')
                                        var regExpEmail = /^[a-zA-Z0-9]+@[a-zA-Z0-9].+[a-z]$/;
                        
                                        $('.alerta_errorSignin').html('<p><b>Errores</b></p>').hide();
                                        $('.alerta_errorLogin').html('<p><b>Errores</b></p>').show();
                    
                                        if( !regExpEmail.test($('#emailLogin').val())) 
                                        {
                                            error = true;
                                            $('<li>').html("El email introducido no es válido").appendTo(listaErrores);
                                        }
                                        
                                        if (error) 
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
