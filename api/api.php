<?php
// Reporta todos los errores de PHP para saber que pasa
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/connectdb.php';

$input = json_decode(file_get_contents('php://input'), true);
$accion = $input['action'] ?? $_GET['action'] ?? '';

try {
    switch ($accion) {
        // --- SECCIÓN PRODUCTOS ---
        case 'obtener':
            // Usamos un LEFT JOIN para que traiga el nombre de la categoría, aunque el producto no tenga ninguna vinculada
            $sql = "SELECT p.*, c.nombre as cat_nombre FROM producto p LEFT JOIN categoria c ON p.categoria_id = c.id ORDER BY p.id DESC";
            $consulta = $myPDO->prepare($sql);
            $consulta->execute();
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($datos);
            break;

        case 'crear':
            $sql = "INSERT INTO producto (id, name, price, stock, categoria_id) VALUES (?, ?, ?, ?, ?)";
            $consulta = $myPDO->prepare($sql);
            // Si el select de categoria llega vacío, lo mandamos como NULL
            $catId = (isset($input['categoria_id']) && $input['categoria_id'] !== "") ? $input['categoria_id'] : null;
            $result = $consulta->execute([$input['id'], $input['name'], $input['price'], $input['stock'], $catId]);
            echo json_encode(['exito' => $result, 'mensaje' => $result ? 'Producto guardado' : 'Error al guardar']);
            break;

        case 'actualizar':
            $sql = "UPDATE producto SET name = ?, price = ?, stock = ?, categoria_id = ? WHERE id = ?";
            $consulta = $myPDO->prepare($sql);
            $catId = (isset($input['categoria_id']) && $input['categoria_id'] !== "") ? $input['categoria_id'] : null;
            $result = $consulta->execute([$input['name'], $input['price'], $input['stock'], $catId, $input['id']]);
            echo json_encode(['exito' => $result, 'mensaje' => $result ? 'Actualizado correctamente' : 'Error']);
            break;

        case 'eliminar':
            $sql = "DELETE FROM producto WHERE id = ?";
            $sentencia = $myPDO->prepare($sql);
            $res = $sentencia->execute([$input['id']]);
            echo json_encode(['exito' => $res]);
            break;

        // --- SECCIÓN CATEGORÍAS ---
        case 'obtener_cats':
            $sql = "SELECT * FROM categoria ORDER BY id ASC";
            $consulta = $myPDO->prepare($sql);
            $consulta->execute();
            echo json_encode($consulta->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'crear_cat':
            $sql = "INSERT INTO categoria (id, nombre) VALUES (?, ?)";
            $consulta = $myPDO->prepare($sql);
            $result = $consulta->execute([$input['id'], $input['nombre']]);
            echo json_encode(['exito' => $result, 'mensaje' => 'Categoría lista']);
            break;

        case 'eliminar_cat':
            $sql = "DELETE FROM categoria WHERE id = ?";
            $consulta = $myPDO->prepare($sql);
            $result = $consulta->execute([$input['id']]);
            echo json_encode(['exito' => $result]);
            break;

        case 'actualizar_cat':
            $sql = "UPDATE categoria SET nombre = ? WHERE id = ?";
            $sentencia = $myPDO->prepare($sql);
            $res = $sentencia->execute([$input['nombre'], $input['id']]);
            echo json_encode(['exito' => $res, 'mensaje' => 'Categoría actualizada']);
            break;

        case 'obtener_stats':
            $p = $myPDO->query("SELECT COUNT(*) FROM producto")->fetchColumn();
            $c = $myPDO->query("SELECT COUNT(*) FROM categoria")->fetchColumn();
            $s = $myPDO->query("SELECT SUM(stock) FROM producto")->fetchColumn() ?: 0;
            echo json_encode(['total_productos' => $p, 'total_categorias' => $c, 'total_stock' => $s]);
            break;

        default:
            echo json_encode(['error' => 'Acción no válida o no especificada']);
            break;
    }
} catch (PDOException $pdoe) {
    // Si falla algo en la base de datos (por ejemplo, si falta la columna categoria_id)
    echo json_encode(['exito' => false, 'error' => "Error de base de datos: " . $pdoe->getMessage(), 'sql_state' => $pdoe->getCode()]);
} catch (Exception $e) {
    echo json_encode(['exito' => false, 'error' => $e->getMessage()]);
}
?>