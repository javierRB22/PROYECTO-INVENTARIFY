<?php
include 'conexion_be.php';

// Lógica de búsqueda si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];

    // Consulta para obtener los productos que coinciden con la búsqueda
    $query_busqueda = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%'";
    $result_busqueda = mysqli_query($conexion, $query_busqueda);

    // Verifica si hay productos
    if ($result_busqueda) {
        echo "<h2>Resultados de la Búsqueda:</h2>";

        // Muestra la tabla de productos
        echo "<table border='1'>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Cantidad de Entrada</th>
                    <th>Total</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result_busqueda)) {
            echo "<tr>
                    <td>{$row['codigo']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['cantidad_entrada']}</td>
                    <td>{$row['total']}</td>
                </tr>";
        }

        echo "</table>";

        // Botón para volver a bienvenido.php
        echo "<a href='bienvenido.php'><button>Volver a Bienvenido</button></a>";
    } else {
        echo "Error al realizar la búsqueda: " . mysqli_error($conexion);
    }
} else {
    // Lógica para mostrar toda la lista sin restricciones
    echo "<h2>Lista de Productos Agregados:</h2>";

    // Consulta para obtener todos los productos
    $query_lista_productos = "SELECT * FROM productos";
    $result_lista_productos = mysqli_query($conexion, $query_lista_productos);

    // Verifica si hay productos
    if ($result_lista_productos) {
        // Muestra la tabla de productos
        echo "<table border='1'>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Cantidad de Entrada</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result_lista_productos)) {
            echo "<tr>
                    <td>{$row['codigo']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['cantidad_entrada']}</td>
                    <td>{$row['total']}</td>
                    <td>
                        <form action='lista_productos.php' method='post'>
                            <input type='hidden' name='codigo_detalle' value='{$row['codigo']}'>
                            <button type='submit' name='detalle_producto'>Ver Detalle</button>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Error al obtener la lista de productos: " . mysqli_error($conexion);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>

<!-- Formulario de búsqueda por nombre -->
<form action="lista_productos.php" method="post">
    <label for="busqueda">Buscar por Nombre:</label>
    <input type="text" name="busqueda" required>
    <button type="submit">Buscar</button>
</form>
