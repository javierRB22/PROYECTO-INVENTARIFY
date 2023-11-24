<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("location: index.php");
    exit();
}

include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $cantidad_entrada = $_POST['cantidad_entrada'];

    // Verificar si el código ya existe
    $verificar_codigo = mysqli_query($conexion, "SELECT * FROM productos WHERE codigo = '$codigo'");

    if (mysqli_num_rows($verificar_codigo) > 0) {
        // Código de producto ya existe, mostrar alerta y redirigir a bienvenido.php
        echo '<script>alert("Código de producto ya existente. Por favor, ingrese uno diferente."); window.location = "bienvenido.php";</script>';
        exit();
    }

    // Calcular el total
    $total = $cantidad_entrada;

    // Insertar datos en la tabla
    $query = "INSERT INTO productos (codigo, nombre, cantidad_entrada, total)
              VALUES (?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $query);

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "ssii", $codigo, $nombre, $cantidad_entrada, $total);

    // Ejecutar la consulta
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<h2>Producto Registrado en la Base de Datos:</h2>";
        echo "<p><strong>Código:</strong> $codigo</p>";
        echo "<p><strong>Nombre del Producto:</strong> $nombre</p>";
        echo "<p><strong>Cantidad de Entrada:</strong> $cantidad_entrada</p>";
        echo "<p><strong>Total:</strong> $total</p>";

        // Botón para volver a bienvenido.php para registrar otro producto
        echo '<a href="bienvenido.php"><button>Registrar otro producto</button></a>';
    } else {
        echo "Error al registrar el producto: " . mysqli_error($conexion);
    }

    // Cerrar la consulta
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>