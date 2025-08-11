<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
            alert('Error Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('index.php');
        </script>";
} else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-Language" content="es-ES">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Control de asistencia <?php include("../include/header_title.php"); ?></title>
        <!--icono en el titulo-->
        <link rel="shortcut icon" href="../img/favicon.ico">
        <!-- Bootstrap -->
        <link href="../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="../Gentella/vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- iCheck -->
        <link href="../Gentella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
        <!-- Datatables -->
        <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

        <style>
            .app-container {
                max-width: 800px;
                margin: 0 auto;
            }

            .time-display {
                margin-bottom: 20px;
            }

            .time-display__text {
                color: #666;
                margin-bottom: 5px;
                font-weight: bolder;
                font-size: 16px;
            }

            .info-text {
                margin-bottom: 20px;
                line-height: 1.5;
            }

            .camera-wrapper {
                width: 100%;
                margin: 20px 0;
                position: relative;
            }

            .camera-feed {
                width: 100%;
                height: 300px;
                background-color: #000;
            }

            #canvas {
                display: none;
            }

            .dni-display {
                width: 200px;
                margin: 20px auto;
                padding: 10px;
                border: 1px solid #ccc;
                background-color: white;
                font-size: 18px;
            }

            .keypad {
                width: 220px;
                margin: 0 auto;
            }

            .keypad__row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .keypad__button {
                width: 60px;
                height: 60px;
                border-radius: 30px;
                border: none;
                background-color: #2196F3;
                color: white;
                font-size: 20px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .keypad__button:hover {
                background-color: #1976D2;
            }

            .keypad__button--zero {
                width: 60px;
            }

            .keypad__button--delete {
                background-color: #f44336;
            }

            .keypad__button--delete:hover {
                background-color: #d32f2f;
            }

            .submit-button {
                width: 100%;
                height: 50px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s;
                margin-top: 20px;
                text-transform: uppercase;
            }

            .submit-button:hover {
                background-color: #388E3C;
            }

            .flash-effect {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: white;
                opacity: 0;
                pointer-events: none;
                z-index: 9999;
                transition: opacity 0.1s ease-out;
            }

            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 4px;
                color: white;
                font-weight: bold;
                opacity: 0;
                transform: translateY(-20px);
                transition: all 0.3s ease-in-out;
                z-index: 10000;
            }

            .notification.show {
                opacity: 1;
                transform: translateY(0);
            }

            .notification.success {
                background-color: #4CAF50;
            }

            .notification.error {
                background-color: #f44336;
            }

            @media (max-width: 600px) {
                .app-container {
                    padding: 10px;
                }

                .camera-feed {
                    height: 200px;
                }

                .keypad__button {
                    width: 50px;
                    height: 50px;
                    font-size: 18px;
                }

                .keypad__button--zero {
                    width: 50px;
                }
            }
        </style>
    </head>

    <body class="nav-md">
        <div id="flash" class="flash-effect"></div>
        <div id="notification" class="notification"></div>

        <div class="container body">
            <div class="main_container">
                <!--menu-->
                <?php include("include/menu_secretaria.php"); ?>

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="clearfix"></div>

                        <div class="row">
                            <center>
                                <h4><b>Control de asistencia</b></h4>
                            </center>

                            <div class="app-container">
                                <div class="time-display">
                                    <h4 class="time-display__text" id="date-display"></h4>
                                    <h4 class="time-display__text" id="time-display"></h4>
                                </div>

                                <div class="info-text">
                                    Ingrese su número de DNI en el campo correspondiente y asegúrese de que sea correcto.
                                    Verifique que su rostro sea claramente visible en el recuadro de la cámara una vez que se active, asegurándose de que no haya obstrucciones como mascarillas, gorros o gafas oscuras.
                                </div>

                                <div class="camera-wrapper">
                                    <video class="camera-feed" id="camera" autoplay playsinline></video>
                                    <canvas id="canvas"></canvas>
                                </div>

                                <div class="dni-display"><b>DNI:</b> <span id="dni-number"></span></div>

                                <div class="keypad">
                                    <div class="keypad__row">
                                        <button class="keypad__button" onclick="addNumber('1')">1</button>
                                        <button class="keypad__button" onclick="addNumber('2')">2</button>
                                        <button class="keypad__button" onclick="addNumber('3')">3</button>
                                    </div>
                                    <div class="keypad__row">
                                        <button class="keypad__button" onclick="addNumber('4')">4</button>
                                        <button class="keypad__button" onclick="addNumber('5')">5</button>
                                        <button class="keypad__button" onclick="addNumber('6')">6</button>
                                    </div>
                                    <div class="keypad__row">
                                        <button class="keypad__button" onclick="addNumber('7')">7</button>
                                        <button class="keypad__button" onclick="addNumber('8')">8</button>
                                        <button class="keypad__button" onclick="addNumber('9')">9</button>
                                    </div>
                                    <div class="keypad__row">
                                        <button class="keypad__button keypad__button--delete" onclick="deleteNumber()">←</button>
                                        <button class="keypad__button keypad__button--zero" onclick="addNumber('0')">0</button>
                                    </div>
                                    <button class="submit-button" onclick="registerAttendance()">Registrar asistencia</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <?php
                include("../include/footer.php");
                ?>
                <!-- /footer content -->
            </div>
        </div>

        <script>
            let dniNumber = '';
            let videoStream = null;

            async function initCamera() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            }
                        }
                    });
                    const videoElement = document.getElementById('camera');
                    videoElement.srcObject = stream;
                    videoStream = stream;
                } catch (err) {
                    console.error('Error al acceder a la cámara:', err);
                    alert('No se pudo acceder a la cámara. Por favor, verifique los permisos.');
                }
            }

            function addNumber(num) {
                if (dniNumber.length < 8) {
                    dniNumber += num;
                    updateDisplay();
                }
            }

            function deleteNumber() {
                if (dniNumber.length > 0) {
                    dniNumber = dniNumber.slice(0, -1);
                    updateDisplay();
                }
            }

            function updateDisplay() {
                document.getElementById('dni-number').textContent = dniNumber;
            }

            function updateDateTime() {
                const now = new Date();

                const options = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long'
                };

                const dateStr = now.toLocaleDateString('es-ES', options);
                const formattedDate = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);

                let hours = now.getHours();
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'P.M.' : 'A.M.';

                hours = hours % 12;
                hours = hours ? hours : 12;

                const timeStr = `${hours}:${minutes}:${seconds} ${ampm}`;

                document.getElementById('date-display').textContent = formattedDate;
                document.getElementById('time-display').textContent = timeStr;

                return {
                    fecha: formattedDate,
                    hora: timeStr
                };
            }

            function capturePhoto() {
                const flash = document.getElementById('flash');
                const video = document.getElementById('camera');
                const canvas = document.getElementById('canvas');

                // Show flash effect
                flash.style.opacity = '1';

                // Capture photo
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Hide flash effect
                setTimeout(() => {
                    flash.style.opacity = '0';
                }, 100);

                return canvas.toDataURL('image/jpeg');
            }

            function downloadPhoto(photoUrl, dni) {
                const link = document.createElement('a');
                link.href = photoUrl;
                link.download = `foto_asistencia_${dni}.jpg`;
                link.click();
            }

            async function registerAttendance() {
                if (dniNumber.length === 8) {
                    try {
                        const dateTime = updateDateTime();
                        const photoUrl = capturePhoto();

                        const registro = {
                            dni: dniNumber,
                            fecha: dateTime.fecha,
                            hora: dateTime.hora,
                            foto_url: photoUrl
                        };

                        const response = await fetch('./operaciones/guardar_asistencia.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                action: 'register_attendance',
                                registro: registro
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            showNotification(result.message, 'success');
                            dniNumber = '';
                            updateDisplay();
                        } else {
                            showNotification(result.message, 'error');
                        }
                    } catch (error) {
                        showNotification('Error al registrar la asistencia', 'error');
                    }
                } else {
                    showNotification('Por favor ingrese un DNI válido de 8 dígitos', 'error');
                }
            }

            function showNotification(message, type) {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.className = `notification ${type}`;

                // Force reflow
                notification.offsetHeight;

                notification.classList.add('show');

                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }

            setInterval(updateDateTime, 1000);

            window.addEventListener('load', () => {
                initCamera();
                updateDateTime();
            });
        </script>

        <!-- jQuery -->
        <script src="../Gentella/vendors/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="../Gentella/vendors/fastclick/lib/fastclick.js"></script>
        <!-- NProgress -->
        <script src="../Gentella/vendors/nprogress/nprogress.js"></script>
        <!-- iCheck -->
        <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>

        <!-- Custom Theme Scripts -->
        <script src="../Gentella/build/js/custom.min.js"></script>

        <?php mysqli_close($conexion); ?>
    </body>

    </html>
<?php
}
