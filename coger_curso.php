<?php
require 'config.php';

try {
    $query = "SELECT curso_id, nombre_curso, fecha_inicio, fecha_final FROM cursos";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        while ($curso = $result->fetch_assoc()) {
            echo "<article class='curso'>";
            echo "<h3>{$curso['nombre_curso']}</h3>";
        
            echo "<p>Fechas: {$curso['fecha_inicio']} - {$curso['fecha_final']}</p>";
            echo "<a href='inscribirse.html?curso_id={$curso['curso_id']}' class='btn'>Inscribirse</a>";
            echo "</article>";
        }
    } else {
        echo "<p>No hay cursos disponibles en este momento.</p>";
    }
} catch (Exception $e) {
    echo "Error al cargar los cursos: " . $e->getMessage();
}

// Cerrar conexión
$mysqli->close();
?>

