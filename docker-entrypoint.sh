#!/bin/bash

# Esperar a que MySQL esté listo
echo "Esperando a que MySQL esté listo..."
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    sleep 1
done

# Crear la tabla de productos
echo "Inicializando la base de datos..."
mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" << EOF
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos productos de ejemplo
INSERT INTO products (name, price, created_at, updated_at) VALUES 
('Laptop', 999.99, NOW(), NOW()),
('Smartphone', 699.99, NOW(), NOW()),
('Headphones', 89.99, NOW(), NOW()),
('Mouse', 24.99, NOW(), NOW());
EOF

echo "Base de datos inicializada correctamente."

# Iniciar Apache en primer plano
apache2-foreground
