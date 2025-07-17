<?php
$conn = new mysqli("127.0.0.1", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

$eventos = [];

$sql = "SELECT id, nombre_cliente, telefono_cliente, direccion_cliente, referencia, servicio, fecha, hora, estado_pago FROM citas";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Colores por estado
    $color = match($row['estado_pago']) {
        'pagado' => '#81c784',
        'pendiente' => '#ffd54f',
        default => '#e57373'
    };

    $eventos[] = [
        'id' => $row['id'],  // Este ID es clave para editar
        'title' => $row['servicio'] . " - " . $row['nombre_cliente'],
        'start' => $row['fecha'] . 'T' . $row['hora'],
        'color' => $color,
        'extendedProps' => [
            'cliente' => $row['nombre_cliente'],
            'telefono' => $row['telefono_cliente'],
            'direccion' => $row['direccion_cliente'],
            'referencia' => $row['referencia'],
            'estado_pago' => $row['estado_pago'],
            'hora' => $row['hora']
        ]
    ];
}

header('Content-Type: application/json');
echo json_encode($eventos);
?>