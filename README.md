# 🛒 Tienda de Ropa - Sistema Completo de E-commerce

Una aplicación Laravel 12 completa con autenticación, gestión de productos, carrito de compras, reportes PDF y administración de inventario.

## ✨ Características

### 🔐 Autenticación y Autorización
- Sistema de login/registro seguro con Laravel Breeze
- Roles diferenciados (Admin y Cliente)
- Control de acceso basado en roles (middleware `VerificarRol`)
- Redirección automática según rol tras login

### 📦 Gestión de Productos
- CRUD completo de productos (Create, Read, Update, Delete)
- Búsqueda y filtrado por categoría y talla
- Validación de datos en backend con mensajes claros
- Protección contra inyecciones SQL (Eloquent ORM)
- Atributos: nombre, descripción, precio, categoría, talla

### 🛒 Carrito de Compras
- Agregar/modificar/eliminar productos
- Soporte para invitados (sesión) y usuarios autenticados (BD)
- Merge automático al iniciar sesión
- Cálculo de subtotal y total
- Validación de stock antes de comprar

### 💰 Checkout y Compras
- Formulario de checkout con validación
- Guardado de compras en base de datos
- Actualización automática de inventario
- Historial de compras por usuario
- Confirmación de compra con detalles

### 📊 Reportes PDF
- Reportes de ventas con filtros por fecha y categoría
- Reportes de inventario con stock actual
- Exportación a PDF con estilos profesionales
- Filtros dinámicos para mayor control

### 📉 Gestión de Inventario
- Registro de entradas y salidas de inventario
- Historial de movimientos con detalles completos
- Alertas de stock bajo (< 10 unidades) en dashboard
- Ajustes manuales de inventario
- Integración automática con compras

### 🎨 Interfaz Moderna
- Diseño responsive (móvil, tablet, desktop)
- Tailwind CSS para estilos consistentes
- Navegación intuitiva con menús claros
- Feedback visual (éxito, error, cargando)
- Dashboard visual para administradores

## 🛠️ Requisitos

- PHP 8.2 o superior
- Composer
- SQLite (o MySQL/PostgreSQL)
- Node.js (opcional, para compilar assets)

## 📥 Instalación

### 1. Clonar el repositorio
```bash
git clone <tu-repositorio>
cd tienda-hombre
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar archivo .env
```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con tus configuraciones:
```env
APP_NAME="Tienda de Ropa"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 4. Crear base de datos
```bash
touch database/database.sqlite
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
```

### 6. Ejecutar seeders
```bash
php artisan db:seed
```

Esto creará:
- 2 roles (Admin y Cliente)
- 33 productos de ejemplo
- 66 registros de inventario

### 7. Iniciar servidor
```bash
php artisan serve
```

Acceder en: `http://localhost:8000`

## 👤 Usuarios de Prueba

Tras ejecutar los seeders, puedes usar:

**Admin:**
- Email: `admin@example.com`
- Contraseña: `password`

**Cliente:**
- Email: `cliente@example.com`
- Contraseña: `password`

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProductoController.php      # CRUD productos
│   │   ├── CarritoController.php       # Carrito
│   │   ├── CompraController.php        # Compras
│   │   ├── ReporteController.php       # Reportes PDF
│   │   ├── AdminDashboardController.php
│   │   ├── AjusteInventarioController.php  # Ajustes manuales
│   │   └── ...
│   └── Middleware/
│       └── VerificarRol.php            # Middleware de roles
├── Models/
│   ├── User.php
│   ├── Producto.php
│   ├── Inventario.php
│   ├── Carrito.php
│   ├── Compra.php
│   ├── MovimientoInventario.php
│   └── ...
└── ...

database/
├── migrations/
│   ├── *_create_users_table.php
│   ├── *_create_productos_table.php
│   ├── *_create_inventarios_table.php
│   ├── *_create_carritos_table.php
│   ├── *_create_compras_table.php
│   └── *_create_movimientos_inventario_table.php
└── seeders/
    ├── RolesSeeder.php
    ├── ProductosSeeder.php
    └── InventarioSeeder.php

resources/
├── views/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── productos/
│   │   ├── ajustes/
│   │   └── ...
│   └── cliente/
│       └── ...
└── css/
    └── app.css

routes/
└── web.php                 # Todas las rutas
```

## 🔧 Configuración de Base de Datos

### Migraciones Disponibles
- `create_users_table` - Tabla de usuarios
- `create_roles_table` - Tabla de roles (Admin, Cliente)
- `create_productos_table` - Productos
- `create_inventarios_table` - Stock por producto
- `create_carritos_table` - Carrito de compras
- `create_compras_table` - Historial de compras
- `create_movimientos_inventario_table` - Auditoría de inventario

### Relaciones Eloquent
```
User → Rol (belongsTo)
Producto → Inventario (hasOne)
Producto → MovimientoInventario (hasMany)
Carrito → Usuario (belongsTo)
Carrito → Producto (belongsTo)
Compra → Usuario (belongsTo)
```

## 🔑 Rutas Principales

### Públicas
- `GET  /catalogo` - Catálogo de productos
- `POST /carrito/agregar` - Agregar al carrito

### Autenticadas (Cliente)
- `GET  /carrito` - Ver carrito
- `GET  /checkout` - Formulario checkout
- `POST /compras/confirmar` - Confirmar compra
- `GET  /cliente/historial` - Historial de compras

### Admin
- `GET  /admin/dashboard` - Dashboard
- `GET  /admin/productos` - Gestión de productos
- `POST /admin/productos` - Crear producto
- `GET  /admin/reportes/ventas` - Reporte de ventas
- `GET  /admin/reportes/inventario` - Reporte de inventario
- `GET  /admin/ajustes` - Historial de ajustes
- `POST /admin/ajustes` - Registrar ajuste manual

## 📊 Funcionalidades Admin

### Dashboard
- Métricas: Total productos, usuarios, compras, ventas mes
- Alertas de stock bajo (< 10 unidades)
- Accesos rápidos a funciones principales

### Gestión de Productos
- Crear productos con cantidad inicial
- Editar: nombre, precio, categoría, talla
- Eliminar productos
- Ver cantidad en inventario
- Búsqueda y paginación

### Reportes
- **Ventas**: Filtra por fechas, categoría, estado
- **Inventario**: Stock actual por producto
- Exportación a PDF

### Ajustes de Inventario
- Registrar entradas (reabastecimiento)
- Registrar salidas (ajustes, devoluciones)
- Historial completo con motivos
- Validación de stock disponible

## 🔄 Flujo de Compra

1. Cliente navega catálogo → Filtra por categoría/talla
2. Agrega producto al carrito (sesión si es invitado)
3. Inicia sesión → Carrito se merge automáticamente
4. Va a checkout → Completa formulario
5. Confirma compra → Se crea orden en BD
6. Inventario se actualiza automáticamente
7. Se registra movimiento de salida
8. Cliente ve confirmación y puede ver historial

## 🚨 Validaciones

### En Compra
- Carrito no vacío
- Stock disponible para cada producto
- Datos de formulario completos

### En Productos
- Nombre requerido
- Precio numérico positivo
- Cantidad numérica positiva
- Protección contra inyecciones SQL (Eloquent)

### En Ajustes
- Producto debe existir
- Cantidad positiva
- No permitir descontar más de lo disponible
- Motivo requerido

## 📝 Seeders

### RolesSeeder
Crea 2 roles:
- `admin` - Acceso completo
- `cliente` - Acceso restringido

### ProductosSeeder
Crea 33 productos con:
- 6 categorías: Camisetas, Camisas, Sudaderas, Chaquetas, Shorts, Pantalones
- 7 tallas: M, L, XL, 30, 32, 34, 36
- Precios aleatorios entre 20-100

### InventarioSeeder
Crea 66 registros de inventario (1 por producto)
- Cantidades aleatorias entre 5-50 unidades

## 🔐 Seguridad

- Contraseñas hasheadas con Bcrypt
- CSRF tokens en formularios
- Queries con Eloquent ORM (protegidas contra SQL injection)
- Validación de entrada en backend
- Middleware de autenticación y roles
- Transacciones en operaciones críticas

## 🐛 Solución de Problemas

### "SQLSTATE[HY000]: General error"
```bash
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate
```

### Cache corrupto
```bash
php artisan cache:clear
php artisan view:clear
```

### Problemas de permisos
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## 📄 Licencia

Este proyecto está bajo licencia MIT.

## ✅ Checklist de Cumplimiento

- ✅ Autenticación con roles diferenciados
- ✅ CRUD completo con validaciones
- ✅ Reportes PDF con filtros
- ✅ Interfaz responsive y usable
- ✅ Base de datos relacional
- ✅ Carrito de compras funcional
- ✅ Módulo de productos completo
- ✅ Gestión de inventario con historial
- ✅ Alertas de stock bajo
- ✅ Ajustes manuales de inventario

## 🤝 Soporte

Para reportar bugs o sugerir mejoras, contacta al desarrollador.

---

**Desarrollado con ❤️ usando Laravel 12**

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
