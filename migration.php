<?php
// Reporta todos los errores de PHP para saber que pasa
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/connectdb.php';

echo "<h2>Iniciando Migración de Base de Datos...</h2>";

try {
    // 1. Crear tabla de categorias
    $sqlCat = "CREATE TABLE IF NOT EXISTS categoria (
        id INT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $myPDO->exec($sqlCat);
    echo "✅ Tabla 'categoria' verificada/creada.<br>";

    // 2. Revisar si la columna categoria_id existe en producto
    $columnas = $myPDO->query("DESCRIBE producto")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('categoria_id', $columnas)) {
        // Añadir la columna si no existe
        $myPDO->exec("ALTER TABLE producto ADD COLUMN categoria_id INT NULL");
        echo "✅ Columna 'categoria_id' añadida a 'producto'.<br>";

        // Añadir la llave foranea (opcional pero recomendado)
        try {
            $myPDO->exec("ALTER TABLE producto ADD CONSTRAINT fk_categoria FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE SET NULL");
            echo "✅ Relación (Foreign Key) creada correctamente.<br>";
        } catch (Exception $eFK) {
            echo "⚠️ No se pudo crear la FK (tal vez ya existe o hay datos incompatibles), pero la columna ya está.<br>";
        }
    } else {
        echo "ℹ️ La columna 'categoria_id' ya existía.<br>";
    }

    echo "<br><strong>🚀 ¡Todo listo! Ya puedes volver al index.php</strong>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>❌ Error de Base de Datos:</h3>";
    echo $e->getMessage();
} catch (Exception $e) {
    echo "<h3 style='color:red'>❌ Error General:</h3>";
    echo $e->getMessage();
}
?>