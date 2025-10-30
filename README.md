# 💬 Chat en tiempo real | Laravel 12 + Reverb

Proyecto desarrollado en **Laravel 12**, utilizando **Reverb** (el servidor oficial de WebSockets de Laravel) para permitir comunicación **en tiempo real** entre usuarios dentro de una interfaz moderna en **Blade**.

---

## 🧠 Descripción

Este proyecto implementa un **chat en tiempo real** sin dependencias externas, utilizando las herramientas nativas del ecosistema Laravel:

- **Laravel Reverb** para WebSockets.
- **Laravel Echo** y **PusherJS** para escuchar eventos en el frontend.
- **Blade** como motor de vistas con un diseño personalizado moderno y responsivo.
- **Broadcasting** para emitir los mensajes instantáneamente a todos los clientes conectados.

---

## 🧩 Requisitos previos

| Requisito | Versión mínima |
|------------|----------------|
| PHP | 8.2 |
| Composer | Última estable |
| Node.js + npm | Node 18+ |
| Base de datos | MySQL o SQLite |
| Puertos disponibles | 8000 (HTTP) y 8080 (WebSocket) |

---

## 🚀 Instalación y configuración

### 1️⃣ Clonar el proyecto
```bash
git clone https://github.com/EliasBenitez02/chat-tiempo-real.git
cd chat-tiempo-real
```

### 2️⃣ Instalar dependencias
```bash
composer install
npm install
```

### 3️⃣ Configurar el entorno
Crear y editar el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_realtime
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret

VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

Generar la clave de aplicación:
```bash
php artisan key:generate
```

---

## ⚙️ Configuración de base de datos

```bash
php artisan migrate
```

Modelo principal:
```php
class Message extends Model {
    protected $fillable = ['user', 'content'];
}
```

---

## 📡 Broadcasting y Reverb

Instalar broadcasting:
```bash
php artisan install:broadcasting
```

Seleccionar la opción **Reverb** cuando lo solicite.

Iniciar Reverb:
```bash
php artisan reverb:start
```

---

## 🧠 Estructura del proyecto

```
app/
 ├─ Events/MessagePosted.php
 ├─ Http/Controllers/ChatController.php
 └─ Models/Message.php

resources/
 ├─ views/chat.blade.php     ← Interfaz moderna del chat
 ├─ js/bootstrap.js
 ├─ js/chat.js
 └─ js/app.js

routes/
 └─ web.php
```

---

## 💬 Flujo general

1. El usuario envía un mensaje desde el formulario (Blade).
2. El controlador **ChatController** valida y guarda el mensaje en la base de datos.
3. Se dispara el evento **MessagePosted**, que se transmite en el canal público `chat`.
4. **Laravel Echo** recibe el evento en tiempo real y lo muestra instantáneamente en pantalla.
5. No se requiere recargar la página.

---

## 🖼️ Vista principal (Blade personalizada)

Archivo: `resources/views/chat.blade.php`

- Diseño con **Inter font** y **gradientes**.
- Animaciones suaves con `@keyframes`.
- Capitalización automática de nombres.
- Scroll automático al final del chat.
- Validación de entradas y control de errores.

Ejemplo visual:
```
💬 Chat en tiempo real
────────────────────────────
👤 Elias:
Hola mundo!
⏱ 2025-10-30 17:45:22
```

---

## 🧰 Comandos útiles

```bash
# Servidor HTTP
php artisan serve --host=0.0.0.0 --port=8000

# Servidor WebSocket
php artisan reverb:start

# Compilar assets
npm run dev
```

---

## 🧾 Tecnologías utilizadas

- Laravel 12  
- Reverb (WebSockets nativos)
- Laravel Echo  
- Blade Templates  
- Vite  
- JavaScript / Fetch API  
- CSS moderno y responsivo  

---

## ✅ Resultado final

- Mensajes instantáneos en múltiples pestañas.
- Sin necesidad de recargar la página.
- Comunicación en tiempo real.
- Diseño atractivo y limpio.
- Compatible con Livewire o Blade.

---

## 📄 Licencia

Este proyecto se distribuye bajo la licencia **MIT**.  
Desarrollado por **Elías Benítez** — *Programación IV, UTN*.
