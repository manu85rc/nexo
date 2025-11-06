# Nexo — Livewire Starter con Filament, Volt y Vite

Aplicación Laravel 12 basada en Livewire Starter Kit, con Filament 4 para panel administrativo, Volt para vistas/auth de Livewire, y Vite + Tailwind 4 para el frontend. Incluye flujos de autenticación, un middleware de validación de trabajador y modelos/migraciones para perfilar trabajadores.

## Requisitos
- PHP >= 8.2
- Composer
- Node.js >= 18 y npm
- MySQL 8 (o Docker + Laravel Sail)

## Tecnologías principales
- Laravel 12, Livewire (Volt, Flux)
- Filament 4 (panel administrativo y recursos de usuario)
- Vite 7, Tailwind CSS 4

## Estructura relevante
- `routes/web.php`: rutas web, dashboard protegido, rutas de ajustes y página `worker`.
- `routes/auth.php`: login, registro, recuperación/confirmación/verificación con Volt.
- `app/Http/Middleware/WorkerValid.php`: middleware que redirige a `worker` si el usuario no tiene registro en `workers`.
- `app/Models/Worker.php`: datos de trabajador (telefono, whatsapp, DNI, CUIT/CUIL, estado fiscal, skills, paths de DNI).
- `database/migrations/*worker*`: tablas `workers` y `worker_profiles` + columnas DNI frente/dorso.
- `app/Filament/Resources/Users/UserResource.php`: recurso de Filament para gestionar usuarios.
- `vite.config.js`: configuración de Vite con `laravel-vite-plugin` y Tailwind.

## Instalación (local)
1) Clonar e instalar dependencias PHP y JS
```bash
composer install
npm install
```

2) Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```
Ajusta en `.env` las credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

3) Migraciones y enlaces de almacenamiento
```bash
php artisan migrate --graceful
php artisan storage:link
```

4) Levantar servidores de desarrollo (opción A: comandos separados)
```bash
php artisan serve
npm run dev
```

Opción B: proceso unificado con concurrencia:
```bash
composer run dev
```
Este comando inicia: servidor Laravel, listener de colas, logs interactivos (pail) y Vite.

## Ejecución con Docker (Laravel Sail)
El proyecto incluye `docker-compose.yml` compatible con Sail.

1) Variables necesarias en `.env` (ejemplo):
```env
APP_PORT=80
VITE_PORT=5173
PMA_PORT=8080
DB_HOST=mysql
DB_DATABASE=nexo
DB_USERNAME=sail
DB_PASSWORD=password
```

2) Levantar servicios
```bash
docker compose up -d
```
Servicios expuestos:
- App: `http://localhost:${APP_PORT}` (por defecto 80)
- Vite: `http://localhost:${VITE_PORT}` (por defecto 5173)
- phpMyAdmin: `http://localhost:${PMA_PORT}` (por defecto 8080)

3) Instalar y preparar dentro del contenedor (si usas Sail)
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --graceful
./vendor/bin/sail artisan storage:link
./vendor/bin/sail npm run dev
```

## Rutas clave
- `/` página de bienvenida.
- `/login`, `/register`, `/forgot-password`, `/reset-password/{token}`: auth con Volt.
- `/dashboard` (auth+verified+`WorkerValid`): redirige a `/worker` si falta el registro en `workers`.
- `/settings/*` (`profile`, `worker`, `password`, `appearance`): sección de ajustes.
- `/worker`: vista para completar datos de trabajador.

Nota: Filament suele exponer el panel en `/admin` por defecto; si agregas un usuario admin y la configuración por defecto, podrás gestionar `UserResource` desde allí.

## Scripts útiles
- `composer run dev`: arranca servidor Laravel, colas, pail y Vite concurrentemente.
- `composer test`: limpia caché de config y ejecuta suite de tests (Pest).
- `npm run dev` / `npm run build`: Vite en modo desarrollo / build de producción.

## Pruebas
```bash
composer test
```

## Despliegue (resumen)
- Ejecutar `npm run build` para generar assets de producción.
- Configurar `.env` de producción y cachear configuración:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
- Correr migraciones: `php artisan migrate --force`.

## Notas y convenciones
- Requiere PHP 8.2+ y Laravel 12.
- Tailwind 4 via `@tailwindcss/vite`.
- El middleware `WorkerValid` asume usuarios autenticados y verifica existencia en `workers`.

## Licencia
MIT
