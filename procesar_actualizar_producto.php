<?php
include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $codigo_actualizar = $_POST['codigo_actualizar'];
    $nueva_cantidad = $_POST['nueva_cantidad'];

    // Verificar si el código existe en la base de datos
    $verificar_codigo = mysqli_query($conexion, "SELECT * FROM productos WHERE codigo = '$codigo_actualizar'");

    if (mysqli_num_rows($verificar_codigo) == 0) {
        // Código no existe, mostrar alerta y salir
        echo '<script>alert("Código de producto no encontrado. Por favor, ingrese uno válido."); window.location = "bienvenido.php";</script>';
        exit();
    }

    // Obtener la cantidad actual y el total existente
    $query_existente = "SELECT cantidad_entrada, total FROM productos WHERE codigo = ?";
    $stmt_existente = mysqli_prepare($conexion, $query_existente);
    mysqli_stmt_bind_param($stmt_existente, "s", $codigo_actualizar);
    mysqli_stmt_execute($stmt_existente);
    $result_existente = mysqli_stmt_get_result($stmt_existente);

    if ($result_existente) {
        $row_existente = mysqli_fetch_assoc($result_existente);

        if ($row_existente) {
            $cantidad_entrada_existente = $row_existente['cantidad_entrada'];
            $total_existente = $row_existente['total'];

            // Calcular el nuevo total y la nueva cantidad
            $nueva_cantidad_total = $cantidad_entrada_existente + $nueva_cantidad;
            $nuevo_total = $total_existente + $nueva_cantidad;

            // Actualizar la cantidad y el total en la base de datos
            $query_actualizar = "UPDATE productos SET cantidad_entrada = ?, total = ? WHERE codigo = ?";
            $stmt_actualizar = mysqli_prepare($conexion, $query_actualizar);
            mysqli_stmt_bind_param($stmt_actualizar, "iis", $nueva_cantidad_total, $nuevo_total, $codigo_actualizar);
            mysqli_stmt_execute($stmt_actualizar);

            if ($stmt_actualizar) {
                echo '<script>alert("Cantidad actualizada exitosamente."); window.location = "bienvenido.php";</script>';
            } else {
                echo "Error al actualizar la cantidad: " . mysqli_error($conexion);
            }
        } else {
            echo "No se encontró ningún producto con el código '$codigo_actualizar'.";
        }
    } else {
        echo "Error al obtener la cantidad existente: " . mysqli_error($conexion);
    }

    // Cerrar la consulta
    mysqli_stmt_close($stmt_existente);
    mysqli_close($conexion);
}
?>
