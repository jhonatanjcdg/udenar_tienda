# 📦 Mi Tienda | Guía de Docker

¡Felicidades! Ahora tu proyecto puede correr en contenedores Docker de forma profesional.

## 🚀 Cómo iniciar el proyecto con Docker

1.  Asegúrate de tener **Docker Desktop** instalado y abierto.
2.  Abre una terminal en la carpeta `Tienda`.
3.  Ejecuta el siguiente comando para levantar todo el sistema:
    ```bash
    docker-compose up -d --build
    ```
4.  Una vez que termine, entra a tu navegador en:
    👉 **[http://localhost:8080](http://localhost:8080)**

---

## 🏗️ Configuración de la Base de Datos

Como Docker crea una base de datos nueva y limpia:
1.  **DEBES** ejecutar el script de migración la primera vez.
2.  Entra a: **[http://localhost:8080/migration.php](http://localhost:8080/migration.php)**
3.  Esto creará las tablas `producto` y `categoria` automáticamente dentro del contenedor.

---

## 🛠️ Comas útiles
*   Ver logs: `docker-compose logs -f`
*   Detener todo: `docker-compose down`
*   Reinstalar si cambias el Dockerfile: `docker-compose up -d --build`

---

## 📁 Archivos creados
*   `Dockerfile`: Define cómo se construye el servidor Apache con PHP 8.2 y PDO.
*   `docker-compose.yml`: Define la red y los servicios de App y Base de Datos (MySQL 8.0).
*   `config/connectdb.php`: Ahora es "inteligente" y detecta si está en Docker o en XAMPP.
