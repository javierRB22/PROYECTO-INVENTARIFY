<?php
include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario y validarlos
    $codigo_actualizar = mysqli_real_escape_string($conexion, $_POST['codigo_actualizar']);
    $nueva_cantidad = intval($_POST['nueva_cantidad']);

    // Verificar si el código existe en la base de datos
    $verificar_codigo = mysqli_query($conexion, "SELECT * FROM productos WHERE codigo = '$codigo_actualizar'");

    if (mysqli_num_rows($verificar_codigo) == 0) {
        // Código no existe, mostrar mensaje y salir
        echo '<script>alert("Código de producto no encontrado. Por favor, ingrese uno válido."); window.location = "bienvenido.php";</script>';
        exit();
    }

    // Obtener la cantidad actual y el total existente
    $query_existente = "SELECT cantidad_entrada, total FROM productos WHERE codigo = ?";
    $stmt_existente = mysqli_prepare($conexion, $query_existente);
    mysqli_stmt_bind_param($stmt_existente, "s", $codigo_actualizar);
    mysqli_stmt_execute($stmt_existente);
    mysqli_stmt_store_result($stmt_existente);

    if (mysqli_stmt_num_rows($stmt_existente) > 0) {
        mysqli_stmt_bind_result($stmt_existente, $cantidad_entrada_existente, $total_existente);
        mysqli_stmt_fetch($stmt_existente);

        // Calcular el nuevo total y la nueva cantidad
        $nueva_cantidad_total = $cantidad_entrada_existente + $nueva_cantidad;
        $nuevo_total = $total_existente + $nueva_cantidad;

        // Actualizar la cantidad y el total en la base de datos
        $query_actualizar = "UPDATE productos SET cantidad_entrada = ?, total = ? WHERE codigo = ?";
        $stmt_actualizar = mysqli_prepare($conexion, $query_actualizar);
        mysqli_stmt_bind_param($stmt_actualizar, "iis", $nueva_cantidad_total, $nuevo_total, $codigo_actualizar);
        $resultado_actualizar = mysqli_stmt_execute($stmt_actualizar);

        if ($resultado_actualizar) {
            // Verificar si ya existe un registro con el mismo código en entrada_nueva_producto
            $query_verificar_existencia = "SELECT * FROM entrada_nueva_producto WHERE codigoProducto = ?";
            $stmt_verificar_existencia = mysqli_prepare($conexion, $query_verificar_existencia);
            mysqli_stmt_bind_param($stmt_verificar_existencia, "s", $codigo_actualizar);
            mysqli_stmt_execute($stmt_verificar_existencia);
            mysqli_stmt_store_result($stmt_verificar_existencia);

            if (mysqli_stmt_num_rows($stmt_verificar_existencia) > 0) {
                // Si ya existe, actualizar la cantidad
                $query_actualizar_entrada = "UPDATE entrada_nueva_producto SET nuevaCantidadEntrada = nuevaCantidadEntrada + ? WHERE codigoProducto = ?";
                $stmt_actualizar_entrada = mysqli_prepare($conexion, $query_actualizar_entrada);
                mysqli_stmt_bind_param($stmt_actualizar_entrada, "is", $nueva_cantidad, $codigo_actualizar);
                $resultado_actualizar_entrada = mysqli_stmt_execute($stmt_actualizar_entrada);

                if (!$resultado_actualizar_entrada) {
                    echo "Error al actualizar la entrada existente: " . mysqli_error($conexion);
                }
            } else {
                // Si no existe, insertar un nuevo registro
                $query_insertar = "INSERT INTO entrada_nueva_producto (codigoProducto, nuevaCantidadEntrada) VALUES (?, ?)";
                $stmt_insertar = mysqli_prepare($conexion, $query_insertar);

                if (!$stmt_insertar) {
                    die("Error al preparar la consulta de inserción: " . mysqli_error($conexion));
                }

                mysqli_stmt_bind_param($stmt_insertar, "si", $codigo_actualizar, $nueva_cantidad);
                $resultado_insertar = mysqli_stmt_execute($stmt_insertar);

                if (!$resultado_insertar) {
                    echo "Error al insertar en la tabla entrada_nueva_producto: " . mysqli_error($conexion);
                }
            }

            // Cerrar las consultas
            mysqli_stmt_close($stmt_existente);
            mysqli_close($conexion);

            // Mostrar mensaje de éxito con un script JavaScript
            echo '<script>alert("Dato actualizado exitosamente."); window.location = "bienvenido.php";</script>';
            exit();
        } else {
            echo "Error al actualizar la cantidad: " . mysqli_error($conexion);
        }
    } else {
        echo "No se encontró ningún producto con el código '$codigo_actualizar'.";
    }

    // Cerrar las consultas
    mysqli_stmt_close($stmt_existente);
    mysqli_close($conexion);
}
?>
