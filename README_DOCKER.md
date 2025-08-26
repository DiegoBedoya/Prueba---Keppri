# Aplicación CRUD de Productos con Docker

Esta es una aplicación CRUD (Create, Read, Update, Delete) simple para gestionar productos. La aplicación está dockerizada para facilitar su despliegue y ejecución.

## Requisitos

- Docker
- Docker Compose

## Instrucciones para ejecutar la aplicación con Docker

1. Clonar el repositorio:
   ```bash
   git clone <url-del-repositorio>
   cd <directorio-del-repositorio>
   ```

2. Construir y levantar los contenedores:
   ```bash
   docker-compose up -d --build
   ```

3. Acceder a la aplicación:
   Abrir el navegador y visitar `http://localhost:8000/products`

## Funcionalidades

- Ver una lista de todos los productos con su nombre y precio
- Añadir nuevos productos proporcionando un nombre y precio
- Editar productos existentes
- Eliminar productos

## Endpoints de la API

- `GET /api/products` - Obtener todos los productos
- `POST /api/products` - Crear un nuevo producto
- `GET /api/products/{id}` - Obtener un producto específico
- `PUT /api/products/{id}` - Actualizar un producto
- `DELETE /api/products/{id}` - Eliminar un producto

## Estructura de contenedores

- **app**: Contenedor PHP con Apache que ejecuta la aplicación web
- **db**: Contenedor MySQL que almacena la base de datos

## Parar los contenedores

```bash
docker-compose down
```

## Parar los contenedores y eliminar los volúmenes (eliminar datos)

```bash
docker-compose down -v
```
