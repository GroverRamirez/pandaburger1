# Pandaburger Backend

Backend completo para el sistema de gestión de restaurante Pandaburger desarrollado en Laravel con MySQL.

## 🏗️ Estructura del Proyecto

### Base de Datos

El sistema incluye las siguientes tablas principales:

- **roles**: Roles de usuario (Administrador, Empleado, Cliente)
- **categorias**: Categorías de productos
- **productos**: Productos del menú
- **usuarios**: Usuarios del sistema
- **clientes**: Clientes/customers
- **pedidos**: Órdenes/pedidos
- **detalle_pedido**: Detalles de cada pedido
- **detalle_pedido_porciones**: Porciones específicas de productos
- **pagos**: Pagos realizados
- **estados_pedido**: Estados de los pedidos
- **estados_pago**: Estados de los pagos
- **metodos_pago**: Métodos de pago disponibles

### Modelos

- `Rol`: Gestión de roles de usuario
- `Categoria`: Categorías de productos
- `Producto`: Productos del menú
- `Usuario`: Usuarios del sistema
- `Cliente`: Clientes/customers
- `Pedido`: Órdenes/pedidos
- `DetallePedido`: Detalles de pedidos
- `DetallePedidoPorcion`: Porciones específicas
- `Pago`: Pagos
- `EstadoPedido`: Estados de pedidos
- `EstadoPago`: Estados de pagos
- `MetodoPago`: Métodos de pago

### Controladores

- `RolController`: CRUD para roles
- `CategoriaController`: CRUD para categorías
- `ProductoController`: CRUD para productos
- `UsuarioController`: CRUD para usuarios
- `ClienteController`: CRUD para clientes
- `PedidoController`: CRUD para pedidos
- `DetallePedidoController`: CRUD para detalles de pedidos
- `PagoController`: CRUD para pagos

## 🚀 Instalación

### Prerrequisitos

- PHP 8.1 o superior
- Composer
- MySQL 8.0 o superior
- Node.js y npm (para assets)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd pandaburger1
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```
   
   Editar el archivo `.env` con la configuración de tu base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pandaburger
   DB_USERNAME=root
   DB_PASSWORD=tu_password
   ```

4. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

5. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

6. **Poblar la base de datos con datos iniciales**
   ```bash
   php artisan db:seed
   ```

7. **Instalar dependencias de Node.js (opcional)**
   ```bash
   npm install
   npm run dev
   ```

## 📊 Datos Iniciales

El seeder creará automáticamente:

### Roles
- Administrador
- Empleado
- Cliente

### Estados de Pedido
- Pendiente
- En Preparación
- Listo
- Entregado
- Cancelado

### Estados de Pago
- Pendiente
- Completado
- Fallido
- Reembolsado

### Métodos de Pago
- Efectivo
- Tarjeta de Crédito
- Tarjeta de Débito
- Transferencia Bancaria
- PayPal

### Categorías de Productos
- Hamburguesas
- Bebidas
- Acompañamientos
- Postres
- Combos

### Usuario Administrador
- Email: admin@pandaburger.com
- Password: password

## 🔌 API Endpoints

### Roles
- `GET /api/roles` - Listar todos los roles
- `POST /api/roles` - Crear nuevo rol
- `GET /api/roles/{id}` - Obtener rol específico
- `PUT /api/roles/{id}` - Actualizar rol
- `DELETE /api/roles/{id}` - Eliminar rol

### Categorías
- `GET /api/categorias` - Listar todas las categorías
- `POST /api/categorias` - Crear nueva categoría
- `GET /api/categorias/{id}` - Obtener categoría específica
- `PUT /api/categorias/{id}` - Actualizar categoría
- `DELETE /api/categorias/{id}` - Eliminar categoría

### Productos
- `GET /api/productos` - Listar todos los productos
- `POST /api/productos` - Crear nuevo producto
- `GET /api/productos/{id}` - Obtener producto específico
- `PUT /api/productos/{id}` - Actualizar producto
- `DELETE /api/productos/{id}` - Eliminar producto
- `GET /api/productos/categoria/{categoriaId}` - Productos por categoría

### Usuarios
- `GET /api/usuarios` - Listar todos los usuarios
- `POST /api/usuarios` - Crear nuevo usuario
- `GET /api/usuarios/{id}` - Obtener usuario específico
- `PUT /api/usuarios/{id}` - Actualizar usuario
- `DELETE /api/usuarios/{id}` - Eliminar usuario
- `GET /api/usuarios/rol/{rolId}` - Usuarios por rol

### Clientes
- `GET /api/clientes` - Listar todos los clientes
- `POST /api/clientes` - Crear nuevo cliente
- `GET /api/clientes/{id}` - Obtener cliente específico
- `PUT /api/clientes/{id}` - Actualizar cliente
- `DELETE /api/clientes/{id}` - Eliminar cliente
- `GET /api/clientes/{cliente}/orders` - Pedidos de un cliente

### Pedidos
- `GET /api/pedidos` - Listar todos los pedidos
- `POST /api/pedidos` - Crear nuevo pedido
- `GET /api/pedidos/{id}` - Obtener pedido específico
- `PUT /api/pedidos/{id}` - Actualizar pedido
- `DELETE /api/pedidos/{id}` - Eliminar pedido
- `GET /api/pedidos/estado/{estadoId}` - Pedidos por estado
- `GET /api/pedidos/usuario/{usuarioId}` - Pedidos por usuario
- `GET /api/pedidos/cliente/{clienteId}` - Pedidos por cliente

### Detalles de Pedido
- `GET /api/detalle-pedido` - Listar todos los detalles
- `POST /api/detalle-pedido` - Crear nuevo detalle
- `GET /api/detalle-pedido/{id}` - Obtener detalle específico
- `PUT /api/detalle-pedido/{id}` - Actualizar detalle
- `DELETE /api/detalle-pedido/{id}` - Eliminar detalle
- `GET /api/detalle-pedido/pedido/{pedidoId}` - Detalles por pedido
- `GET /api/detalle-pedido/producto/{productoId}` - Detalles por producto

### Pagos
- `GET /api/pagos` - Listar todos los pagos
- `POST /api/pagos` - Crear nuevo pago
- `GET /api/pagos/{id}` - Obtener pago específico
- `PUT /api/pagos/{id}` - Actualizar pago
- `DELETE /api/pagos/{id}` - Eliminar pago
- `GET /api/pagos/pedido/{pedidoId}` - Pagos por pedido
- `GET /api/pagos/estado/{estadoPagoId}` - Pagos por estado
- `GET /api/pagos/metodo/{metodoPagoId}` - Pagos por método

## 🔧 Comandos Útiles

### Desarrollo
```bash
# Ejecutar servidor de desarrollo
php artisan serve

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ver rutas disponibles
php artisan route:list
```

### Base de Datos
```bash
# Revertir todas las migraciones
php artisan migrate:reset

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Crear nueva migración
php artisan make:migration nombre_migracion

# Crear nuevo seeder
php artisan make:seeder NombreSeeder
```

## 🛡️ Seguridad

- Todas las contraseñas se hashean usando bcrypt
- Validación de datos en todos los endpoints
- Soft deletes implementado para mantener integridad referencial
- Relaciones de base de datos con claves foráneas

## 📝 Notas Importantes

1. **Configuración de Base de Datos**: Asegúrate de que MySQL esté configurado correctamente y que la base de datos `pandaburger` exista.

2. **Permisos**: El sistema incluye un sistema de roles básico. Puedes expandirlo según tus necesidades.

3. **Autenticación**: El sistema está preparado para usar Laravel Sanctum para autenticación API.

4. **Validación**: Todos los endpoints incluyen validación de datos apropiada.

5. **Relaciones**: Los modelos incluyen todas las relaciones necesarias para consultas eficientes.

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles. 