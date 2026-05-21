# Peto-BubbaLova 
Proyecto de gestión para **BobbaLova** un sistema para el control de pedidos, inventario de materiales, y administración.

## Instrucciones de Ejecución

### Paso 1 - Clonar el proyecto y preparar dependencias
Clona el repositorio e instala las dependencias de PHP y JavaScript:
```bash
# Clonar e ingresar a la carpeta del proyecto
git clone <url-del-repositorio>
cd Peto-BubbaLova

# Instalar dependencias
composer install
npm install
```

### Paso 2 - Configurar el entorno (.env) y generar la APP_KEY
Es necesario crear el archivo de configuración `.env` a partir del ejemplo:
```bash
# En Windows (CMD/PowerShell)
copy .env.example .env

# En Linux/macOS
cp .env.example .env
```
Luego, genera la clave de encriptación de la aplicación:
```bash
php artisan key:generate
```
> [!IMPORTANT]
> Configura las credenciales de tu base de datos local en el archivo `.env` (parámetros `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.) antes de continuar.

### Paso 3 - Compilar recursos del Frontend (Vite)
Para construir los archivos del frontend y evitar errores como `Vite manifest not found`, compila los recursos con:
```bash
npm run build
```

---

### Paso 4 - Iniciar el servidor
Inicia el servidor local con:
```bash
# Servidor Backend local en el puerto 8001
php artisan serve --port=8001

# Compilación Frontend en tiempo real (opcional para desarrollo)
npm run dev

# Para poder activar las tareas Programadas / Envío de correos automáticos:
php artisan schedule:work
```

---

# Credenciales de Prueba (Roles)

### Administrador
* **Usuario Administrador**: `adrianyamq@gmail.com`
* **Contraseña**: `password`
* `El administrador puede cambiar y generar las contraseñas de nuevos usuarios`

### Cocineros
* **Usuario1**: `kan@gmail.com`
* **Contraseña**: `12345678`

* **Usuario2**: `camellia@gmail.com`
* **Contraseña**: `12345678`

### Cajeros
* **Usuario2**: `kana@gmail.com`
* **Contraseña**: `12345678`

* **Usuario2**: `mari@gmail.com`
* **Contraseña**: `12345678`

---

# Diagrama Entidad-Relacion

![Diagrama Entidad-Relación](der.png)
