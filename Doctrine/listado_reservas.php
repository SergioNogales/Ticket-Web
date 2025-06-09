<html lang="es">
<?php
    require_once 'reserva_controladora.php';
    session_start();
    $controladora = new reserva_controladora();
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $controladora->procesarSeleccion($_POST);
    }
?>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="listado_eventos.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <section class="noSignin">
                <div class="noneContenedor">
                    <div class="alerta_Login">
                        <h1>Debe iniciar sesión previo al acceso a tus reservas</h1>
                    </div>
                </div>
            </section>
            <section class="main eventos reservas">
                <div id="titulo"><h1>Mis Reservas</h1></div>
                <div class="barra2"></div>
                <?php
                    if(!empty($_SESSION['usuario']))
                    {
                        $reservas = $controladora->getControladora()->buscarReservas($_SESSION['usuario']->getEmail());
                        if(!empty($reservas))
                        {
                            foreach ($reservas as $reserva) 
                            {
                                $evento = $reserva->getEvento();
                                $descripcion = htmlspecialchars($evento->getDescripcion());
                                $nombre = htmlspecialchars($evento->getNombre());
                                $tipo = htmlspecialchars($evento->getTipo());
                                $precio = htmlspecialchars($evento->getPrecio());
                                $plazas = htmlspecialchars($evento->getPlazas());
                                $lugar = htmlspecialchars($evento->getLugar());
                                $fInicio = $evento->getFechaInicio();
                                $fFin = $evento->getFechaFin();
                                    
                                echo '<article class="evento">';
                                echo '    <div class="fila">';
                                echo '        <div>';
                                echo '            <div class="tituloEvento">' . $nombre . '</div>';
                                echo '            <div class="descEvento"> Categoria: ' . $tipo . '</div>';
                                echo '            <div class="descEvento"> Entrada de: ' . $reserva->getNombre() . '</div>';
                                echo '            <div class="fecha">Fecha inicio: ' . $fInicio->format('Y-m-d') . '    hasta: ' . $fFin->format('Y-m-d') . '</div>';                                
                                echo '        </div>';
                                echo '        <form method="post" action="listado_reservas.php">';
                                echo '            <input type="hidden" name="reserva" value="' . $reserva->getId() . '">';
                                echo '                 <button type="submit" class="eventoBoton" name = "detalles" value = "detalles">Detalles</button>';
                                echo '        </form>';
                                echo '    </div>';
                                echo '</article>';
                            }
                        }
                        else
                        {
                            echo    '<div class="noneContenedor">';
                            echo        '<div class="alerta_Login">';
                            echo            '<h1>Usted no tiene reservas.</h1>';
                            echo        '</div>';
                            echo    '</div>';
                        }  

                        echo "<script>$('.noSignin').hide();</script>";
                        echo "<script>$('.reservas').show();</script>";
                    }
                    else
                    {
                        echo "<script>$('.reservas').hide();</script>";
                        echo "<script>$('.noSignin').show();</script>";
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
    </body>
</html>