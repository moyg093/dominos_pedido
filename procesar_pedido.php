<?php
// Configuración del correo de destino
$destino = "moyg093@gmail.com";
$asunto = "Nuevo Pedido - Domino's Pizza";

// Validar que la solicitud sea por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Limpieza y recepción de datos para evitar inyecciones
    $metodo_entrega = isset($_POST['delivery_type']) ? htmlspecialchars($_POST['delivery_type']) : 'No especificado';
    $tamano         = isset($_POST['size'])          ? htmlspecialchars($_POST['size'])          : 'No especificado';
    $tipo_masa      = isset($_POST['crust'])         ? htmlspecialchars($_POST['crust'])         : 'No especificado';
    $nombre         = isset($_POST['name'])          ? htmlspecialchars(trim($_POST['name']))    : '';
    $telefono       = isset($_POST['phone'])         ? htmlspecialchars(trim($_POST['phone']))   : '';
    $direccion      = isset($_POST['address'])       ? htmlspecialchars(trim($_POST['address'])) : '';

    // Manejar el array de ingredientes (checkboxes)
    $ingredientes = "Ninguno";
    if (isset($_POST['toppings']) && is_array($_POST['toppings'])) {
        $ingredientes_limpios = array_map('htmlspecialchars', $_POST['toppings']);
        $ingredientes = implode(", ", $ingredientes_limpios);
    }

    // Validar campos obligatorios básicos
    if (empty($nombre) || empty($telefono) || empty($direccion)) {
        echo "<script>alert('Por favor completa todos los campos obligatorios.'); window.history.back();</script>";
        exit;
    }

    // 2. Construcción del cuerpo del correo (Formato HTML)
    $mensaje = "
    <html>
    <head>
      <title>Detalles del Pedido</title>
      <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 500px; }
        h2 { color: #E31837; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        td.label { font-weight: bold; width: 40%; color: #006491; }
      </style>
    </head>
    <body>
      <div class='container'>
        <h2>🍕 Nuevo Pedido Recibido</h2>
        <table>
          <tr><td class='label'>Método de Entrega:</td><td>{$metodo_entrega}</td></tr>
          <tr><td class='label'>Tamaño:</td><td>{$tamano}</td></tr>
          <tr><td class='label'>Tipo de Masa:</td><td>{$tipo_masa}</td></tr>
          <tr><td class='label'>Ingredientes:</td><td>{$ingredientes}</td></tr>
          <tr><td class='label'>Cliente:</td><td>{$nombre}</td></tr>
          <tr><td class='label'>Teléfono:</td><td>{$telefono}</td></tr>
          <tr><td class='label'>Dirección:</td><td>{$direccion}</td></tr>
        </table>
      </div>
    </body>
    </html>
    ";

    // 3. Cabeceras del correo para enviar formato HTML
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Pedidos Domino's <no-reply@tu-dominio.com>" . "\r\n";
    $headers .= "Reply-To: " . $destino . "\r\n";

    // 4. Envío del correo
    if (mail($destino, $asunto, $mensaje, $headers)) {
        // Redirección o mensaje de éxito
        echo "<script>
                alert('¡Tu pedido se ha enviado con éxito!');
                window.location.href = 'index.html';
              </script>";
    } else {
        echo "<script>
                alert('Hubo un error al enviar el pedido. Inténtalo de nuevo.');
                window.history.back();
              </script>";
    }

} else {
    // Si intentan acceder al archivo directamente por la URL
    header("Location: index.html");
    exit;
}
?>