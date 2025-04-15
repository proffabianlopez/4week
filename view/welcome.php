<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <button class="close-btn" id="close-btn">&times;</button>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="login.php">Salir</a></li>
        </ul>
    </div>
    <button class="open-btn" id="open-btn">&#9776;</button>
    <div class="welcome-container">
        <h1>Bienvenido/a</h1>
        <p>Gracias por unirte a nuestra plataforma. ¡Esperamos que disfrutes de la experiencia!</p>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-btn');
        const closeBtn = document.getElementById('close-btn');

        openBtn.addEventListener('click', () => {
            sidebar.style.transform = 'translateX(0)';
            openBtn.style.display = 'none'; // Ensure the open button is hidden
        });

        closeBtn.addEventListener('click', () => {
            sidebar.style.transform = 'translateX(-100%)';
            openBtn.style.display = 'block'; // Ensure the open button is shown
        });
    </script>
</body>
</html>
