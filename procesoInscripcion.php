<?php
require 'config.php'; // Importa la conexión a la base de datos


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id = $_POST['curso_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Validar campos básicos
    if (empty($nombre) || empty($apellido) || empty($email)) {
        die("Todos los campos son obligatorios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico no válido.");
    }

    // Iniciar la transacción
    $mysqli->begin_transaction();
    
    try {
        // Verificar si el estudiante ya está registrado
        $stmt = $mysqli->prepare("SELECT estudiante_id FROM estudiantes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiante = $result->fetch_assoc();
        $stmt->close();

        if (!$estudiante) {
            // Registrar nuevo estudiante
            $stmt = $mysqli->prepare("INSERT INTO estudiantes (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $apellido, $email, $telefono);
            $stmt->execute();
            $estudiante_id = $stmt->insert_id;
            $stmt->close();
        } else {
            $estudiante_id = $estudiante['estudiante_id'];
        }

        // Registrar la inscripción
        $stmt = $mysqli->prepare("INSERT INTO inscipciones (estudiante_id, curso_id, status) VALUES (?, ?, 'active')");
        $stmt->bind_param("ii", $estudiante_id, $curso_id);
        $stmt->execute();
        $stmt->close();

        // Confirmar transacción
        $mysqli->commit();

        echo "Inscripción completada con éxito.";
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $mysqli->rollback();
        echo "Error al procesar la inscripción: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}

// Cerrar conexión
$mysqli->close();
?>

