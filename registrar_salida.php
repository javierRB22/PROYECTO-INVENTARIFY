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

                // Actualizar el total en la base de datos
                $query_actualizar = "UPDATE productos SET total = ? WHERE codigo = ?";
                $stmt_actualizar = mysqli_prepare($conexion, $query_actualizar);
                mysqli_stmt_bind_param($stmt_actualizar, "is", $total_nuevo, $codigo);
                mysqli_stmt_execute($stmt_actualizar);

                if ($stmt_actualizar) {
                    echo "<h2>Salida Registrada en la Base de Datos:</h2>";
                    echo "<p><strong>Código:</strong> $codigo</p>";
                    echo "<p><strong>Cantidad de Salida:</strong> $cantidad_salida</p>";
                    echo "<p><strong>Total Actualizado:</strong> $total_nuevo</p>";

                    // Botón para volver a bienvenido.php
                    echo '<a href="bienvenido.php"><button>Volver a Bienvenido</button></a>';
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

    // Cerrar la consulta
    mysqli_stmt_close($stmt_existente);
    mysqli_close($conexion);
}
?>
