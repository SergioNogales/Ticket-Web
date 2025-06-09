<html lang="es">

<?php
    require_once 'solicitudes_controladora.php';
    session_start();
    $controladora = new solicitudes_controladora();
    $success = false;
    $errorRol = false;
    if(!empty($_SESSION['usuario']))
    {
        $tempUser = $_SESSION['usuario'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if($tempUser->getRol() !== "admin")
        {
            $controladora->solicitudCliente($_POST);
        }
        else
        {
            $controladora->solicitudAdmin($_POST);
        }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sergio Nogales Sanz">
    <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
    <link rel="stylesheet" href="solicitudes.css">
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
        <section class="none">
            <div class="noneContenedor">
                <div class="alerta_Login">
                    <h1>Debe iniciar sesión previo a la solicitud de roles</h1>
                </div>
            </div>
        </section>
        <section class="main cliente">
            <form class="margin" id="solicitarRol" method="post" action="solicitudes.php">
                <h1>Solicitar Rol</h1>
                <?php
                    $tempUser = $_SESSION["usuario"];
                    echo '<div class="rolActual"> Rol actual: ' . $tempUser->getRol() . '</div>';
                ?>
                <div class="alerta_exito"> 
                    La solicitud fue enviada correctamente.
                </div>
                <div class="alerta_pendiente"> 
                    La anterior solicitud sigue pendiente.
                </div>
                <div class="alerta_errorRol"> 
                    <?php 
                        if (!empty($errorRol))
                        {
                            echo "<p><b>Errores</b></p>";
                            echo "<ul><li>" . htmlspecialchars($errorRol) . "</li></ul>";
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
                    <div class="label">Solicitar como</div>
                    <div class="field">
                        <select id="rol" name="rol">
                            <option value="">Selecciona una opción</option>
                            <?php
                                if($tempUser->getRol() === 'cliente')
                                {
                                    echo '<option value="promotor">Promotor</option>';
                                }
                            ?>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="filaLogin">
                    <button type="submit" class="boton">Enviar Solicitud</button>
                </div>
                <script>
                    $(document).ready(function () {
                        $('#solicitarRol').submit(function (event) {
                            var error = false;
                            var listaErrores = $('<ul>');

                            $('.alerta_errorRol').html('<p><b>Errores</b></p>').hide();
                            $('.alerta_exito').hide();

                            if($('#rol').val() === "")
                            {
                                error = true;
                                $('<li>').text("Debes seleccionar un rol").appendTo(listaErrores);
                            }

                            if(error) 
                            {
                                var alertas = $('.alerta_errorRol');
                                listaErrores.appendTo(alertas);
                                alertas.show();
                                event.preventDefault();
                            }
                        });
                    });
                </script>
            </form>
        </section>
        <section class="main eventos admin">
            <h1>Solicitudes de roles</h1>
            <?php
                $usuarios = $controladora->getControladora()->getUsuarios();
                foreach ($usuarios as $usuario) {
                    $estado = "";
                    $email = htmlspecialchars($usuario->getEmail());
                    $nombre = htmlspecialchars($usuario->getNombre());
                    $rolActual = htmlspecialchars($usuario->getRol());
                    $solicitudes = $controladora->buscarSolicitudes($usuario);

                    foreach($solicitudes as $solicitud)
                    {
                        if($solicitud)
                        {
                            $estado = htmlspecialchars($solicitud->getEstado());
                            $nuevoRol = htmlspecialchars($solicitud->getRolSolicitado());
                        }
                            
                        if($estado !== "")
                        {
                            echo '<article class="evento">';
                            echo '    <div class="fila">';
                            echo '        <div>';
                            echo '            <div class="tituloEvento">Solicitud de Rol</div>';
                            echo '            <div class="descEvento">Usuario: ' . $nombre . '</div>';

                            if($estado === "pendiente")
                            {
                                echo '            <div class="fecha">Rol actual: ' . $rolActual . '    Solicita: ' . $nuevoRol . '</div>';
                                echo '        </div>';
                                echo '        <form method="post" action="solicitudes.php">';
                                echo '            <input type="hidden" name="email" value="' . $email . '">';
                                echo '            <div class="fila">';
                                echo '                 <button type="submit" class="eventoBoton" name = "accion" value = "aceptar">Aceptar</button>';
                                echo '                 <button type="submit" class="eventoBoton" name = "accion" value = "denegar">Denegar</button>';
                                echo '            </div>';
                                echo '        </form>';
                            }
                            if($estado === "aprobada")
                            {
                                echo '            <div class="fecha"> Solicita: ' . $nuevoRol . '</div>';
                                echo '        </div>';
                                echo '        <div id="aprobado"> ✅APROBADA </div>';
                            }
                            if($estado === "denegada")
                            {
                                echo '            <div class="fecha"> Solicita: ' . $nuevoRol . '</div>';
                                echo '        </div>';
                                echo '        <div id="denegado"> ❌DENEGADA </div>';
                            }
                            echo '    </div>';
                            echo '</article>';
                            echo '<div class="barra3"></div>';
                        }
                    }
                }
            ?>
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
    <script>
        $(document).ready(function () {
            <?php
                if (!empty($_SESSION["usuario"])) 
                {
                    $tempUser = $_SESSION["usuario"];
                    if ($tempUser->getRol() === "cliente" || $tempUser->getRol() === "promotor") 
                    {
                        echo "$('.cliente').show();";
                        echo "$('.none').hide();";
                    }
                    if ($tempUser->getRol() === "admin") 
                    {
                        echo "$('.admin').show();";
                        echo "$('.none').hide();";
                    }
                    $solicitud = $controladora->getControladora()->buscarPendiente($tempUser);
                    if (!empty($solicitud))
                    {
                        echo "$('.alerta_pendiente').show();";
                        echo "$('#rol').hide();";
                        echo "$('.label').hide();";
                    }
                }
            ?>
        });
    </script>
</body>
</html>