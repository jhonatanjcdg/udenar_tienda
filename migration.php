<?php
/**
 * ARCHIVO: migration.php
 * Descripción: Estructura de BD completa. Ideal para Docker limpio.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/connectdb.php';

echo "<h2>🛠️ Iniciando Migración Completa (Docker/Local)...</h2>";

try {
    // 1. Tabla CATEGORIA
    $sqlCat = "CREATE TABLE IF NOT EXISTS categoria (
        id INT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $myPDO->exec($sqlCat);
    echo "✅ Tabla 'categoria' lista.<br>";

    // 2. Tabla PRODUCTO
    $sqlProd = "CREATE TABLE IF NOT EXISTS producto (
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        stock INT NOT NULL DEFAULT 0,
        categoria_id INT NULL,
        FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $myPDO->exec($sqlProd);
    echo "✅ Tabla 'producto' lista.<br>";

    // 3. Verificación de columna (por si ya existía la tabla producto antes de categorias)
    $stmt = $myPDO->query("DESCRIBE producto");
    $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('categoria_id', $columnas)) {
        $myPDO->exec("ALTER TABLE producto ADD COLUMN categoria_id INT NULL");
        try {
            $myPDO->exec("ALTER TABLE producto ADD CONSTRAINT fk_categoria FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE SET NULL");
            echo "✅ Relación Foreign Key añadida.<br>";
        } catch (Exception $e) {
        }
    }

    echo "<br><strong>🚀 ¡Entorno configurado con éxito!</strong>";
    echo "<br><a href='index.php'>Regresar al Inicio</a>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>❌ Error BD:</h3>" . $e->getMessage();
}
?>