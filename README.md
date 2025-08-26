# Prueba---Keppri
Aplicación CRUD de Productos con Docker
Descripción
Esta aplicación es un CRUD (Create, Read, Update, Delete) simple para gestionar productos, desarrollada como parte de una prueba técnica. La aplicación está compuesta por un backend en PHP (usando una implementación simplificada basada en Laravel) y un frontend con HTML, CSS (Bootstrap) y JavaScript vanilla.

La aplicación ha sido dockerizada para facilitar su despliegue y ejecución en cualquier entorno que tenga Docker instalado.

Funcionalidades
Visualización de productos en una tabla (nombre, precio)
Creación de nuevos productos
Edición de productos existentes
Eliminación de productos
Tecnologías Utilizadas
Backend: PHP (implementación simplificada basada en Laravel)
Frontend: HTML, CSS (Bootstrap 5), JavaScript
Base de datos: MySQL
Contenedorización: Docker y Docker Compose
Estructura del Proyecto

prueba-keppri/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductController.php      # Controlador para productos
│   └── Models/
│       └── Product.php                    # Modelo de datos
├── database/
│   └── migrations/
│       └── 2023_08_26_000000_create_products_table.php  # Migración
├── public/
│   ├── index_simple.php                   # Punto de entrada simplificado
│   ├── products_page.php                  # Vista HTML principal
│   └── server.php                         # Manejador de rutas
├── resources/
│   └── views/
│       └── products.blade.php             # Vista Blade (Laravel)
├── routes/
│   └── api.php                            # Definiciones de rutas API
├── .dockerignore                          # Archivos ignorados en Docker
├── docker-compose.yml                     # Configuración de servicios Docker
├── docker-entrypoint.sh                   # Script de inicialización
├── Dockerfile                             # Instrucciones para imagen Docker
├── run.php                                # Script para ejecutar sin Docker
└── setup.php                              # Script de inicialización de BD


Requisitos
Para ejecutar esta aplicación necesitas:

Docker
Docker Compose
Instrucciones de Instalación y Ejecución
Usando Docker (Recomendado)
Clonar este repositorio:
git clone https://github.com/DiegoBedoya/Prueba---Keppri.git
cd Prueba---Keppri

Construir y levantar los contenedores:
docker-compose up -d --build

Acceder a la aplicación: Abrir en el navegador: http://localhost:8000/products

Sin Docker (Ejecución Manual)
Clonar este repositorio:
git clone https://github.com/DiegoBedoya/Prueba---Keppri.git
cd Prueba---Keppri


Configurar la base de datos MySQL:

Crear una base de datos llamada prueba_keppri
Configurar usuario y contraseña en public/index_simple.php si es necesario
Ejecutar el script de configuración para crear la tabla de productos:
php setup.php
Iniciar el servidor PHP:
php -S localhost:8000 -t public


Acceder a la aplicación: Abrir en el navegador: http://localhost:8000/products

API Endpoints
La aplicación proporciona los siguientes endpoints RESTful:

GET /api/products - Obtener todos los productos
POST /api/products - Crear un nuevo producto (requiere name y price en el cuerpo)
GET /api/products/{id} - Obtener un producto específico
PUT /api/products/{id} - Actualizar un producto (requiere name y price en el cuerpo)
DELETE /api/products/{id} - Eliminar un producto


structura de la Base de Datos
Tabla: products
Campo	Tipo	Descripción
id	BIGINT	Identificador único del producto (PK)
name	VARCHAR(255)	Nombre del producto
price	DECIMAL(10,2)	Precio del producto
created_at	TIMESTAMP	Fecha de creación
updated_at	TIMESTAMP	Fecha de última actualización
