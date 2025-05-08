<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Sergio Nogales Sanz">
        <title>Práctica Final Desarrollo Web I Sergio Nogales Sanz / Landing Page</title>
        <link rel="stylesheet" href="ticket.css">
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
                <div id="titulo"><h1>Mis ticket</h1></div>
                <div class="barra2"></div>

                <div class="fila" id="ticket">
                    <div id="capsulaTicket">
                        <div class="margin2">
                            <div class="tituloEvento">Katherine Ryan - Battleaxe</div>
                            <div class="descEvento">Madrid, Teatro Pavón</div>
                            <div class="fecha">Martes 21 de Enero 2025</div>
                            Cod entrada: 5442292832729839002
                        </div>
                        <img src="assets/qr.png" class="qr">
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