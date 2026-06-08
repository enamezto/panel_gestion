# Panel de Gestión Web - SDi Digital Group

Panel de gestión web para el control de versiones y despliegue de módulos en entornos a3ERP.

## Requisitos

- PHP 8.2 o superior
- Composer
- PostgreSQL
- Node.js y npm
- Extensiones PHP: pdo_pgsql y pgsql habilitadas en `php.ini`

## Instalación

1. Clonar el repositorio:
```bash
git clone <https://github.com/enamezto/panel_gestion.git>
cd panel-gestion
```

2. Instalar dependencias de PHP:
```bash
composer install
```

3. Instalar dependencias de Node:
```bash
npm install
```

4. Copiar el archivo de entorno:
```bash
cp .env.example .env
```

5. Configurar las variables de entorno en `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=panel_gestion
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
APP_URL=http://127.0.0.1:8000
```

6. Generar la clave de la aplicación:
```bash
php artisan key:generate
```

7. Crear la base de datos en PostgreSQL y ejecutar las migraciones con datos de prueba:
```bash
php artisan migrate --seed
```

8. Compilar los assets:
```bash
npm run dev
```

9. Arrancar el servidor:
```bash
php artisan serve
```

## Acceso al panel

- **URL:** http://127.0.0.1:8000
- **Email:** tecnico@sdi.es
- **Password:** password123

## Tests

Crear previamente la base de datos `panel_gestion_test` en PostgreSQL, luego ejecutar:

```bash
php artisan test
```

## Health check

```
GET http://127.0.0.1:8000/api/v1/health
```

## Notas
En entornos Linux, puede ser necesario dar permisos de escritura a las carpetas de almacenamiento:
```bash
chmod -R 775 storage bootstrap/cache
```
