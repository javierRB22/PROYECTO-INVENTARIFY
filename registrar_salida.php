<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("location: index.php");
    exit();
}

include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $codigo = $_POST['codigo_salida'];
    $cantidad_salida = $_POST['cantidad_salida'];

    // Obtener el total existente
    $query_existente = "SELECT total FROM productos WHERE codigo = ?";
    $stmt_existente = mysqli_prepare($conexion, $query_existente);
    mysqli_stmt_bind_param($stmt_existente, "s", $codigo);
    mysqli_stmt_execute($stmt_existente);
    $result_existente = mysqli_stmt_get_result($stmt_existente);

    if ($result_existente) {
        $row_existente = mysqli_fetch_assoc($result_existente);

        if ($row_existente) {
            $total_existente = $row_existente['total'];

            // Verificar si hay suficiente cantidad para realizar la salida
            if ($total_existente >= $cantidad_salida) {
                // Calcular el nuevo total
                $total_nuevo = $total_existente - $cantidad_salida;

                // Actualizar el total en la tabla productos
                $query_actualizar = "UPDATE productos SET total = ? WHERE codigo = ?";
                $stmt_actualizar = mysqli_prepare($conexion, $query_actualizar);
                mysqli_stmt_bind_param($stmt_actualizar, "is", $total_nuevo, $codigo);
                $resultado_actualizar = mysqli_stmt_execute($stmt_actualizar);

                if ($resultado_actualizar) {
                    // Verificar si ya existe un registro con el mismo código en salida_cantidad_producto
                    $query_verificar_existencia = "SELECT * FROM salida_cantidad_producto WHERE codigoProducto = ?";
                    $stmt_verificar_existencia = mysqli_prepare($conexion, $query_verificar_existencia);
                    mysqli_stmt_bind_param($stmt_verificar_existencia, "s", $codigo);
                    mysqli_stmt_execute($stmt_verificar_existencia);
                    mysqli_stmt_store_result($stmt_verificar_existencia);

                    if (mysqli_stmt_num_rows($stmt_verificar_existencia) > 0) {
                        // Si ya existe, actualizar la cantidad
                        $query_actualizar_salida = "UPDATE salida_cantidad_producto SET salidaProducto = salidaProducto + ? WHERE codigoProducto = ?";
                        $stmt_actualizar_salida = mysqli_prepare($conexion, $query_actualizar_salida);
                        mysqli_stmt_bind_param($stmt_actualizar_salida, "is", $cantidad_salida, $codigo);
                        $resultado_actualizar_salida = mysqli_stmt_execute($stmt_actualizar_salida);

                        if (!$resultado_actualizar_salida) {
                            echo "Error al actualizar la cantidad de salida en salida_cantidad_producto: " . mysqli_error($conexion);
                        }
                    } else {
                        // Si no existe, insertar un nuevo registro
                        $query_insertar_salida = "INSERT INTO salida_cantidad_producto (codigoProducto, salidaProducto) VALUES (?, ?)";
                        $stmt_insertar_salida = mysqli_prepare($conexion, $query_insertar_salida);

                        if (!$stmt_insertar_salida) {
                            die("Error al preparar la consulta de inserción de salida: " . mysqli_error($conexion));
                        }

                        mysqli_stmt_bind_param($stmt_insertar_salida, "is", $codigo, $cantidad_salida);
                        $resultado_insertar_salida = mysqli_stmt_execute($stmt_insertar_salida);

                        if (!$resultado_insertar_salida) {
                            echo "Error al insertar en la tabla salida_cantidad_producto: " . mysqli_error($conexion);
                        }
                    }

                    // Mostrar mensaje de éxito con un script JavaScript
                    echo '<script>alert("Salida Registrada en la Base de Datos."); window.location = "bienvenido.php";</script>';
                } else {
                    echo "Error al actualizar la cantidad de salida: " . mysqli_error($conexion);
                }
            } else {
                echo "No hay suficiente cantidad disponible para realizar la salida.";
            }
        } else {
            echo "No se encontró ningún producto con el código '$codigo'.";
        }
    } else {
        echo "Error al obtener el total existente: " . mysqli_error($conexion);
    }

    // Cerrar las consultas
    mysqli_stmt_close($stmt_existente);
    mysqli_close($conexion);
}
?>
