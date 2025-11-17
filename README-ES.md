# Tienda Ropa Hombre

Proyecto Laravel (en español): `tienda-ropa-hombre` — tienda de ropa para hombre con roles (admin/cliente), carrito persistente en SQLite, catálogo con filtros dinámicos, CRUD de productos e inventarios, reportes PDF y API interna.

## Requisitos
- PHP >= 8.1
- Composer
- Node.js y npm
- SQLite (no requiere servidor adicional)

## Configuración rápida (Windows PowerShell)

1. Clona el repositorio o copia el proyecto:

   ```powershell
   git clone <tu-repo> tienda-ropa-hombre
   cd tienda-ropa-hombre
   ```

2. Instala dependencias PHP y JS:

   ```powershell
   composer install
   npm install
   ```

3. Configura el entorno (.env):

   - Copia `.env.example` a `.env`.
   - Crea el archivo SQLite:

   ```powershell
   copy .env.example .env
   New-Item -Path database -Name database.sqlite -ItemType File -Force
   # En .env: DB_CONNECTION=sqlite, DB_DATABASE=${PWD}\\database\\database.sqlite
   ```

   Edita `.env` y pon:

   ```text
   DB_CONNECTION=sqlite
   DB_DATABASE=${PWD}\\database\\database.sqlite
   ```

4. Generar key de aplicación:

   ```powershell
   php artisan key:generate
   ```

5. Ejecutar migraciones y seeders:

   ```powershell
   php artisan migrate
   php artisan db:seed --class=DatabaseSeeder
   ```

6. Instalar y compilar assets (Tailwind CSS):

   ```powershell
   npm run build
   ```

7. Ejecutar servidor local:

   ```powershell
   php artisan serve
   ```

8. Paquetes recomendados (reportes PDF):

   ```powershell
   composer require barryvdh/laravel-dompdf
   ```

## Notas
- Código en español: modelos (`Producto`, `Inventario`, `Carrito`, `Compra`, `Rol`), controladores y rutas.
- Middleware: `App\\Http\\Middleware\\VerificarRol` para proteger rutas por rol.
- Frontend: Tailwind CSS y JS con Fetch API en `/public/js/catalogo.js`.
- Carrito persistente en BD: tabla `carritos` con `usuario_id`, `producto_id`, `cantidad`.
- Reportes PDF: agregar rutas y controladores que usen `barryvdh/laravel-dompdf`.

## Siguientes pasos recomendados
- Añadir seeders para roles y usuarios.
- Implementar reportes PDF y endpoints API REST adicionales.
- Preparar `.gitignore` y revisar que no subas archivos sensibles (.env, database)

Si quieres, puedo añadir seeders y completar la implementación de reportes y la API REST en español.
