<?php
/**
 * ARCHIVO: connectdb.php
 * Descripción: Configura y establece la conexión con la base de datos MySQL.
 */

// Si existen variables de entorno (Docker), las usamos. Si no, usamos las locales (XAMPP).
$HOSTDB = getenv('DB_HOST') ?: "localhost";
$NAMEDB = getenv('DB_NAME') ?: "tienda_db";
$USERDB = getenv('DB_USER') ?: "jhonatan";
$PWDB = getenv('DB_PASS') ?: 'XkgKPVpcwy2[b(/I';

try {
    $hostPDO = "mysql:host=$HOSTDB;dbname=$NAMEDB;charset=utf8mb4";
    $myPDO = new PDO($hostPDO, $USERDB, $PWDB);

    // Modo error: EXCEPTION (lanza excepciones si algo falla)
    $myPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Error de conexión DB [$HOSTDB]: " . $e->getMessage());
}
?>