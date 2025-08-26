FROM php:8.2-apache

# Instalar extensiones PHP necesarias y herramientas
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar archivos de la aplicación
COPY . /var/www/html/

# Configurar entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer puerto 80
EXPOSE 80

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Dar permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Configurar entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
