# Módulo de Usuarios y Autenticación

## Descripción

Este módulo proporciona una gestión completa de usuarios, roles y permisos para el sistema Panda Burger. Incluye funcionalidades de autenticación, autorización y gestión de perfiles de usuario.

## Características Principales

### 🔐 Autenticación y Autorización
- **Login personalizado** con validación de credenciales
- **Registro de usuarios** con validación de datos
- **Gestión de sesiones** con tokens de recordatorio
- **Proveedor de autenticación personalizado** para usar columnas específicas de la base de datos

### 👥 Gestión de Usuarios
- **CRUD completo** de usuarios
- **Filtros avanzados** por nombre, email, rol y estado
- **Paginación** para grandes volúmenes de datos
- **Soft delete** para mantener historial
- **Estados de usuario** (activo/inactivo)
- **Avatares** con iniciales automáticas

### 🛡️ Gestión de Roles y Permisos
- **Sistema de roles** con permisos granulares
- **Asignación de permisos** a roles
- **Gestión visual** de permisos con checkboxes
- **Validación** para evitar eliminar roles con usuarios asignados
- **Estadísticas** de usuarios por rol

### 👤 Perfil de Usuario
- **Información personal** editable
- **Cambio de contraseña** con validación
- **Subida de avatares** con preview
- **Configuración de seguridad** (preparado para 2FA)
- **Historial de acceso** y actividad

## Estructura de Archivos

### Frontend (Vue 3 + TypeScript)

```
resources/js/
├── pages/Usuarios/
│   ├── Index.vue          # Lista de usuarios con filtros
│   ├── Roles.vue          # Gestión de roles y permisos
│   └── Profile.vue        # Perfil de usuario
├── components/Usuarios/
│   └── UserStats.vue      # Componente de estadísticas
└── config/
    └── navigation.ts      # Configuración de navegación
```

### Backend (Laravel)

```
app/
├── Http/Controllers/
│   ├── UsuarioController.php    # Controlador principal de usuarios
│   ├── RolController.php        # Controlador de roles
│   └── Auth/
│       ├── RegisteredUserController.php
│       ├── AuthenticatedSessionController.php
│       └── CustomLoginRequest.php
├── Models/
│   ├── User.php                 # Modelo de usuario
│   └── Rol.php                  # Modelo de rol
└── Providers/
    └── CustomUserProvider.php   # Proveedor de autenticación
```

## Base de Datos

### Tabla `users`
```sql
- id (BIGINT UNSIGNED, AUTO_INCREMENT)
- rol_id (BIGINT UNSIGNED, FOREIGN KEY)
- nombre (VARCHAR(60))
- correo_electronico (VARCHAR(120), UNIQUE)
- telefono (VARCHAR(30), NULLABLE)
- password_hash (VARCHAR(255))
- last_login_at (TIMESTAMP, NULLABLE)
- deleted_at (TIMESTAMP, NULLABLE) - Soft deletes
- created_at, updated_at (TIMESTAMP)
```

### Tabla `roles`
```sql
- id (SMALLINT UNSIGNED, AUTO_INCREMENT)
- nombre (VARCHAR(50), UNIQUE)
- descripcion (VARCHAR(255), NULLABLE)
- created_at, updated_at (TIMESTAMP)
- deleted_at (TIMESTAMP, NULLABLE) - Soft deletes
```

### Tabla `permisos` (Pivot)
```sql
- id (SMALLINT UNSIGNED, AUTO_INCREMENT)
- nombre (VARCHAR(50))
- descripcion (VARCHAR(255))
- created_at, updated_at (TIMESTAMP)
```

## Rutas API

### Usuarios
```
GET    /usuarios                    # Lista de usuarios
POST   /usuarios                    # Crear usuario
GET    /usuarios/{id}              # Ver usuario
PUT    /usuarios/{id}              # Actualizar usuario
DELETE /usuarios/{id}              # Eliminar usuario
PATCH  /usuarios/{id}/restore      # Restaurar usuario
```

### Roles
```
GET    /roles                      # Lista de roles
POST   /roles                      # Crear rol
GET    /roles/{id}                # Ver rol
PUT    /roles/{id}                # Actualizar rol
DELETE /roles/{id}                # Eliminar rol
PUT    /roles/{id}/permisos       # Actualizar permisos del rol
```

### API Endpoints
```
GET    /api/profile                # Obtener perfil del usuario
PUT    /api/profile               # Actualizar perfil
PUT    /api/profile/password      # Cambiar contraseña
GET    /api/usuarios/statistics   # Estadísticas de usuarios
GET    /api/roles                 # Lista de roles (API)
GET    /api/permisos              # Lista de permisos
```

## Componentes UI

### Página de Usuarios (`Index.vue`)
- **Tabla responsive** con información de usuarios
- **Filtros avanzados** por búsqueda, rol y estado
- **Modales** para crear/editar usuarios
- **Confirmación** para eliminar usuarios
- **Paginación** con navegación

### Página de Roles (`Roles.vue`)
- **Grid de tarjetas** para roles
- **Gestión de permisos** con modal dedicado
- **Estadísticas** de usuarios por rol
- **Validación** para roles con usuarios asignados

### Página de Perfil (`Profile.vue`)
- **Información personal** editable
- **Cambio de contraseña** con validación
- **Subida de avatar** con preview
- **Configuración de seguridad**

## Características Técnicas

### Autenticación Personalizada
- **CustomUserProvider** para usar `correo_electronico` y `password_hash`
- **CustomLoginRequest** con validación personalizada
- **Soporte para remember tokens**

### Validaciones
- **Email único** en la base de datos
- **Contraseñas seguras** con reglas de Laravel
- **Validación de permisos** antes de eliminar roles
- **Soft deletes** para mantener integridad de datos

### Seguridad
- **Hashing de contraseñas** con bcrypt
- **Validación de CSRF** en todas las rutas
- **Middleware de autenticación** en rutas protegidas
- **Preparado para 2FA** (estructura lista)

## Uso

### 1. Acceder al módulo
Navega a la sección "Gestión de Usuarios" en el sidebar:
- **Usuarios**: Lista y gestión de usuarios
- **Roles**: Gestión de roles y permisos
- **Mi Perfil**: Configuración personal

### 2. Crear un usuario
1. Haz clic en "Nuevo Usuario"
2. Completa el formulario con:
   - Nombre completo
   - Correo electrónico
   - Teléfono (opcional)
   - Rol asignado
   - Contraseña y confirmación
3. Guarda el usuario

### 3. Gestionar roles
1. Ve a "Roles" en el sidebar
2. Crea un nuevo rol o edita uno existente
3. Asigna permisos específicos al rol
4. Los usuarios heredarán los permisos del rol

### 4. Editar perfil
1. Ve a "Mi Perfil"
2. Actualiza tu información personal
3. Cambia tu contraseña si es necesario
4. Sube una foto de perfil

## Estadísticas Disponibles

- **Total de usuarios** en el sistema
- **Usuarios activos** vs inactivos
- **Distribución por roles**
- **Últimos accesos** de usuarios
- **Actividad de roles** y permisos

## Configuración

### Variables de Entorno
```env
# Configuración de autenticación
AUTH_DRIVER=custom
AUTH_PROVIDER=custom
```

### Middleware
```php
// En routes/usuarios.php
Route::middleware(['auth'])->group(function () {
    // Rutas protegidas
});
```

## Próximas Mejoras

- [ ] **Autenticación de dos factores** (2FA)
- [ ] **Notificaciones por email** para cambios de cuenta
- [ ] **Auditoría de cambios** en usuarios y roles
- [ ] **Importación/exportación** de usuarios
- [ ] **Sincronización** con sistemas externos
- [ ] **Dashboard avanzado** con gráficos
- [ ] **API REST completa** para integraciones

## Troubleshooting

### Error de autenticación
- Verifica que las columnas `correo_electronico` y `password_hash` existan
- Confirma que el `CustomUserProvider` esté registrado
- Revisa los logs de Laravel para errores específicos

### Problemas con roles
- Asegúrate de que la tabla `roles` tenga datos
- Verifica las relaciones en los modelos
- Confirma que los permisos estén correctamente asignados

### Errores de validación
- Revisa las reglas de validación en los controladores
- Confirma que los campos requeridos estén presentes
- Verifica la unicidad de emails y nombres de roles

## Contribución

Para contribuir al módulo de usuarios:

1. **Fork** el repositorio
2. **Crea una rama** para tu feature
3. **Implementa** los cambios
4. **Añade tests** para nuevas funcionalidades
5. **Documenta** los cambios
6. **Envía un pull request**

## Licencia

Este módulo es parte del proyecto Panda Burger y está bajo la misma licencia del proyecto principal. 