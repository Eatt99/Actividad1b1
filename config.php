


$mysqli = new mysqli("localhost:3307", "root","","sistema_inscripcion");

if ($mysqli -> connect_error) {
    echo "Error de conexion: " . $mysqli -> connect_error;
}
?>