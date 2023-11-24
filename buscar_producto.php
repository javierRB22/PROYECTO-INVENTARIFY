<?php
include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Recuperar el código de búsqueda
    $codigo_busqueda = $_GET['codigo_busqueda'];

    // Realizar la consulta para obtener los datos del producto
    $query_buscar = "SELECT * FROM productos WHERE codigo = ?";
    $stmt_buscar = mysqli_prepare($conexion, $query_buscar);
    mysqli_stmt_bind_param($stmt_buscar, "s", $codigo_busqueda);
    mysqli_stmt_execute($stmt_buscar);
    $result_buscar = mysqli_stmt_get_result($stmt_buscar);

    if ($result_buscar) {
        $row_buscar = mysqli_fetch_assoc($result_buscar);

        if ($row_buscar) {
            // Mostrar los datos del producto encontrado
            echo "<h2>Producto Encontrado:</h2>";
            echo "<p><strong>Código:</strong> {$row_buscar['codigo']}</p>";
            echo "<p><strong>Nombre del Producto:</strong> {$row_buscar['nombre']}</p>";
            echo "<p><strong>Cantidad de Entrada:</strong> {$row_buscar['cantidad_entrada']}</p>";
            echo "<p><strong>Total:</strong> {$row_buscar['total']}</p>";

            // Script JavaScript para mostrar una alerta con los resultados y redirigir a bienvenido.php
            echo "<script>";
            echo "var mensaje = 'Producto Encontrado:\\nCódigo: {$row_buscar['codigo']}\\nNombre: {$row_buscar['nombre']}\\nCantidad de Entrada: {$row_buscar['cantidad_entrada']}\\nTotal: {$row_buscar['total']}';";
            echo "alert(mensaje);";
            echo "window.location.href = 'bienvenido.php';";
            echo "</script>";
        } else {
            // Código no existe, mostrar alerta y redirigir a bienvenido.php
            echo "<script>";
            echo "alert('Código no existe.');";
            echo "window.location.href = 'bienvenido.php';";
            echo "</script>";
            exit(); // Asegura que el script se detenga después de redirigir
        }
    } else {
        echo "Error al realizar la búsqueda: " . mysqli_error($conexion);
    }

    // Cerrar la consulta
    mysqli_stmt_close($stmt_buscar);
    mysqli_close($conexion);
}
?>
