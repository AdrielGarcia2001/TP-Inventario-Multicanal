<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Inventario multicanal

La aplicación expone la misma lógica de productos mediante REST y GraphQL, publica cambios por Laravel Reverb y ofrece una consulta gRPC local.

### Preparar y ejecutar

```powershell
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
if (!(Select-String -Path .env -Pattern '^APP_KEY=.+')) { php artisan key:generate }
if (!(Test-Path database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite }
php artisan migrate
npm install
npm run build
```

En `.env`, configure `BROADCAST_CONNECTION=reverb`, `QUEUE_CONNECTION=database`, y genere valores propios para `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET` y `GRPC_INTERNAL_KEY`. `VITE_REVERB_*` toma los valores correspondientes de Reverb. `.env.example` deja las claves vacías intencionalmente: no reutilice claves públicas o compartidas. No suba `.env` ni `database/database.sqlite` al repositorio. Ejecute cada proceso en una terminal separada:

```powershell
php artisan serve
php artisan reverb:start
php artisan queue:work
npm run dev
```

### API REST

- `GET /api/productos` y `GET /api/productos/{id}`
- `POST /api/productos` con `nombre`, `precio` y `stock`
- `PATCH /api/productos/{id}` para modificar cualquiera de esos campos
- `DELETE /api/productos/{id}`

La creación responde `201`, la eliminación `204`, la validación `422` y los IDs inexistentes `404`.

### GraphQL y actualizaciones en vivo

Lighthouse atiende `POST /graphql`. Ejemplos:

```graphql
{ productos { id nombre precio stock } }
mutation { crearProducto(nombre: "Mouse", precio: 18000, stock: 20) { id nombre stock } }
mutation { actualizarStock(id: 1, stock: 8) { id stock } }
```

Abra `/inventario` en dos pestañas para ver las actualizaciones transmitidas por el canal público `inventario`.

### gRPC local

Configure un valor largo y aleatorio en `GRPC_INTERNAL_KEY` de `.env`. En PowerShell, cargue esa misma clave en la terminal del servidor y arránquelo:

```powershell
$env:GRPC_INTERNAL_KEY = (Get-Content .env | Where-Object { $_ -match '^GRPC_INTERNAL_KEY=' } | Select-Object -First 1).Split('=', 2)[1]
node grpc/server.cjs
```

En otra terminal, consulte un producto existente:

```powershell
node grpc/client.cjs 1
```

El servidor gRPC escucha solo en `127.0.0.1` y consulta el endpoint interno `/api/interno/productos/{id}` protegido con la clave compartida. No exponga este servicio sin TLS ni publique la clave. Para usar el cliente Python opcional desde `grpc/`, instale `grpcio` y `grpcio-tools`, genere los stubs con `python -m grpc_tools.protoc -I. --python_out=. --grpc_python_out=. producto.proto` y ejecute `python client.py 1`.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
