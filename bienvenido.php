<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Por favor debe iniciar sesión");
        window.location = "index.php";
    </script>
    ';

    session_destroy();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTARIFY</title>
    <link rel="stylesheet" href="estilos2.css">
</head>
<body>

<video autoplay muted loop id="video-fondo">
        <source src="pexels-vanessa-loring-5866259 (2160p).mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>

    <h1>Bienvenido a Inventarify</h1>

    
    

    <!-- Formulario para registrar producto -->
    <form class="registro-form" action="procesar_registro_producto.php" method="POST">
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" required class="codigo-input">

        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" required class="nombre-input">

        <label for="cantidad_entrada">Cantidad de Entrada:</label>
        <input type="number" name="cantidad_entrada" required class="cantidad-input">

        <input type="submit" value="Registrar Producto" class="submit-button">
        <input type="hidden" name="accion" value="registrar">
        
    </form>

    <!-- Formulario para actualizar cantidad de entrada -->
<form class="actualizar-form" action="procesar_actualizar_producto.php" method="POST">
    <label for="codigo_actualizar">Código del Producto a Actualizar:</label>
    <input type="text" name="codigo_actualizar" required>
    <label for="nueva_cantidad">Nueva Cantidad de Entrada:</label>
    <input type="number" name="nueva_cantidad" required>
    <button type="submit">Actualizar Cantidad</button>
</form>


    <!-- Formulario para registrar la salida -->
    <form class="salida-form" action="registrar_salida.php" method="post">
        <label for="codigo_salida">Código del Producto:</label>
        <input type="text" name="codigo_salida" required class="codigo-salida-input">

        <label for="cantidad_salida">Cantidad de Salida:</label>
        <input type="number" name="cantidad_salida" required class="cantidad-salida-input">

        <button type="submit" class="salida-button">Registrar Salida</button>
    </form>

    <!-- Formulario de búsqueda por código -->
    <form class="busqueda-form" action="buscar_producto.php" method="get">
        <label for="codigo_busqueda">Buscar por Código:</label>
        <input type="text" name="codigo_busqueda" required class="codigo-busqueda-input">

        <button type="submit" class="busqueda-button">Buscar</button>
    </form>

    <!-- Botón para ver la lista de productos -->
<a href="lista_productos.php"><button>Ver Lista de Productos</button></a>


    <form action="cerrar_sesion.php" method="post">
        <button type="submit" class="cerrar-sesion-button">Cerrar Sesión</button>
    </form>


    
</body>
</html>
