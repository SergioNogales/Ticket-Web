<html lang="es">
<?php
    require_once 'reserva_controladora.php';
    session_start();
    $addActividad = false;
    $idActividad = "";
    $controladora = new reserva_controladora();

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $controladora->procesarReserva($_POST);
    }
?>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="reserva.css">
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
                        <h1>Debe tener sesión inciada para comprar un ticket a un evento.</h1>
                    </div>
                </div>
            </section>
            <section class="main">
                <div id="titulo"><h1>Detalles de la reserva</h1></div>
                <div class="barra2"></div>
            </section>
            <section id="evento">
                <?php
                    if(!empty($_SESSION['usuario']) && isset($_SESSION['evento']) && $_SESSION['evento'] !== "")
                    {
                        $tempEvent = $controladora->getControladora()->buscarEvento($_SESSION['evento']->getId());
                        echo '<script>$(".noSignin").hide();</script>';
                        echo '<div class="main">';
                        echo '<div class="fila" id="ticket">';
                        echo '    <div id="capsulaTicket">';
                        echo '        <div class="margin2">';
                        echo '            <div class="tituloEvento">'.$tempEvent->getNombre().'</div>';
                        echo '            <div class="descEvento">' . $tempEvent->getLugar() .'</div>';
                        echo '            <div class="fecha">'. $tempEvent->getFechaInicio()->format('Y-m-d') .'    Hasta:'. $tempEvent->getFechaFin()->format('Y-m-d') .'</div>';
                        echo '            Cod entrada: WIP';
                        echo '            <form action="reserva.php" method="post" style="display: inline;">';
                        echo '                <button type="submit" name="accion" value="reserva" class="boton">Cancelar Reserva</button>';
                        echo '            </form>';
                        echo '        </div>';
                        echo '        <img src="assets/qr.png" class="qr">';
                        echo '    </div>';
                        echo '</div>';
                        $actividades = $controladora->getControladora()->buscarActividades($tempEvent);
                        if(!empty($actividades))
                        {
                            echo '<div class="actividades negro">';
                            echo '<div class="margin">';
                            echo '<div class="tituloActividades">Actividades programadas:</div>';
                            foreach($actividades as $actividad)
                            {
                                echo '<li>'. $actividad->getNombre() . '   Descripción: ' . $actividad->getDescripcion() . '   Fecha:' . $actividad->getFecha()->format('Y-m-d')  . '   Lugar:' . $actividad->getLugar() . '   Plazas:' . $actividad->getPlazas() .'</li>';
                                $reserva = $controladora->buscarReservaActividad($actividad->getId());
                                if(!empty($reserva))
                                {
                                    echo '<li>RESERVADA</li>';
                                    echo '            <form action="reserva.php" method="post" style="display: inline;">';
                                    echo '                <input type="hidden" name="reserva" value="'.$reserva->getReserva()->getId().'">';
                                    echo '                <input type="hidden" name="reservaId" value="'.$reserva->getId().'">';
                                    echo '                <button type="submit" name="accion" value="borrarActividad" class="boton">Cancelar Reserva</button>';
                                    echo '            </form>';
                                }
                                else
                                {
                                    echo '            <form action="reserva.php" method="post" style="display: inline;">';
                                    echo '                <input type="hidden" name="actividad" value="'.$actividad->getId().'">';
                                    echo '                <button type="submit" name="accion" value="reservaActividad" class="boton">Reservar Actividad</button>';
                                    echo '            </form>';
                                }
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        else
                        {
                            echo '<div class="tituloActividades">No hay actividades programadas</div>';
                        }
                    }
                    else
                    {
                        echo '<script>$(".evento").hide();</script>';
                    }
                ?>
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