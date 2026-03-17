# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para MySQL y PDO
RUN docker-php-ext-install pdo pdo_mysql

# Habilitamos el módulo rewrite de Apache (común en PHP)
RUN a2enmod rewrite

# Copiamos los archivos de nuestra app al contenedor
COPY . /var/www/html/

# Ajustamos permisos para que Apache pueda leer archivos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponemos el puerto 80
EXPOSE 80
