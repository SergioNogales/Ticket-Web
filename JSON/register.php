<html lang="es">

<?php
require_once 'usuario.php';
session_start();
$file = 'usuarios.json';
$errorSignin = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["newpassword"]);

    $usuarios = json_decode(file_get_contents($file), true);

    foreach ($usuarios as $usuario) 
    {
        if ($usuario["email"] === $email) 
        {
            $errorSignin = "El correo ya está registrado.";
        }
    }

    if (empty($email)) 
    {
        $errorSignin = "El campo email está vacío.";
    } 
    elseif (empty($password) || empty($confirmPassword)) 
    {
        $errorSignin = "Los campos de contraseña están vacíos.";
    } 
    elseif ($password !== $confirmPassword) 
    {
        $errorSignin = "Las contraseñas no coinciden.";
    } 

    if (!$errorSignin) 
    {
        $nuevoUsuario = new usuario($email, $password, "cliente", trim($_POST["telephone"]), trim($_POST["direccion"]), trim($_POST["localidad"]), trim($_POST["name"]), trim($_POST["codigo_postal"]), trim($_POST["tarjeta"]), trim($_POST["mes_caducidad"]), trim($_POST["year_caducidad"]), trim($_POST["ccv"]));
        $usuarios[] = $nuevoUsuario;
        file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT));
        $success = true;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sergio Nogales Sanz">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="register.css">
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
                    <a class="ignoreLink" href="login.php">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="main">
            <div id="titulo"><h1>Identifícate</h1></div>
            <div class="barra2"></div>
            <div class="fila" id="loginContainer">
                <div id="registerdiv">
                    <form class="margin" id="register" method="post" action="register.php">
                        <h1>Registro</h1>
                        <div class="alerta_exito"> 
                            El registro se completó con éxito.
                        </div>
                        <div class="alerta_errorSignin"> 
                            <?php 
                                if (!empty($errorSignin))
                                {
                                    echo "<p><b>Errores</b></p>";
                                    echo "<ul><li>" . htmlspecialchars($errorSignin) . "</li></ul>";
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
                            <div class="field"><input type="text" id="emailSignin" name="email"></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Teléfono</div>
                            <div class="field"><input type="tel" id="telephone" name="telephone"></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Nombre Completo</div>
                            <div class="field"><input type="text" id="name" name="name"></div>
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
                                <input type="number" id="mes_caducidad" name="mes_caducidad" placeholder="Mes" style="width: 60px;">
                                <input type="number" id="año_caducidad" name="year_caducidad" placeholder="Año" >
                            </div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">CCV</div>
                            <div class="field"><input type="number" id="ccv" name="ccv"></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Contraseña</div>
                            <div class="field"><input type="password" id="passwordSignin" name="password"></div>
                        </div>
                        <div class="filaLogin">
                            <div class="label">Repetir Contraseña</div>
                            <div class="field"><input type="password" id="newpasswordSignin" name="newpassword"></div>
                        </div>
                        <div class="filaLogin">
                            <button type="submit" class="boton">Registrarse</button>
                        </div>
                        <script>
                                $(document).ready(function() {
                                    $('#register').submit(function(event) {
                       
                                        var error = false;
                                        var listaErrores = $('<ul>')
                                        var regExpEmail = /^[a-zA-Z0-9]+@[a-zA-Z0-9].+[a-z]$/;

                                        var ccv = /^[0-9]{3}$/;
                                        var numeroTarjeta = /^[0-9]{16}$/;
                                        var telef = /^(?:\+?[0-9]{1,3})?[0-9]{9}$/;

                                        $('.alerta_errorSignin').html('<p><b>Errores</b></p>').show();
                                        $('.alerta_errorLogin').html('<p><b>Errores</b></p>').hide();

                                        if( !regExpEmail.test($('#emailSignin').val())) 
                                        {
                                            error = true;
                                            $('<li>').html("El email introducido no es válido").appendTo(listaErrores);
                                        }

                                        if( !telef.test($('#telephone').val())) 
                                        {
                                            error = true;
                                            $('<li>').html("El telefono introducido no es válido").appendTo(listaErrores);
                                        }

                                        if( !ccv.test($('#ccv').val())) 
                                        {
                                            error = true;
                                            $('<li>').html("El ccv introducido no es válido").appendTo(listaErrores);
                                        }

                                        if( !numeroTarjeta.test($('#tarjeta').val())) 
                                        {
                                            error = true;
                                            $('<li>').html("La tarjeta de crédito introducida no es válida").appendTo(listaErrores);
                                        }

                                        if( $('#newpasswordSignin').val() != $('#passwordSignin').val()) 
                                        {
                                            error = true;
                                            $('<li>').html("Las contraseñas son distintas").appendTo(listaErrores);
                                        }
                        
                                        if( error ) 
                                        {
                                            var alertas = $('.alerta_errorSignin');
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
