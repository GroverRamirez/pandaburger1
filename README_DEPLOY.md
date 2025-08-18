# 🚀 Guía de Despliegue en Railway - PandaBurger

Esta guía te llevará paso a paso para desplegar tu aplicación Laravel + Vue.js en Railway.

## 📋 Prerrequisitos

- Cuenta en [Railway](https://railway.app)
- Repositorio en GitHub con tu código
- Conocimientos básicos de Laravel

## 🎯 Pasos para el Despliegue

### 1. Preparar el Repositorio

Asegúrate de que estos archivos estén en la raíz de tu repo:
- `build-app.sh`
- `run-cron.sh` 
- `run-worker.sh`
- `.env.railway.example`
- `railway.json`

### 2. Crear Proyecto en Railway

1. Ve a [Railway Dashboard](https://railway.app/dashboard)
2. Haz clic en **"New Project"**
3. Selecciona **"Deploy from GitHub repo"**
4. Conecta tu cuenta de GitHub si no lo has hecho
5. Selecciona tu repositorio `GroverRamirez/pandaburger1`
6. Haz clic en **"Deploy Now"**

### 3. Configurar Base de Datos MySQL

1. En tu proyecto Railway, haz clic en **"New"**
2. Selecciona **"Database"** → **"MySQL"**
3. Espera a que se cree la base de datos
4. Anota las credenciales que aparecen

### 4. Configurar Variables de Entorno

1. En tu proyecto, ve a la pestaña **"Variables"**
2. Haz clic en **"Raw Editor"**
3. Copia y pega el contenido de `.env.railway.example`
4. **IMPORTANTE**: Modifica estas variables:

```bash
# Cambia por tu dominio Railway
APP_URL=https://tu-app.railway.app

# Genera una nueva APP_KEY localmente
APP_KEY=base64:tu-nueva-app-key-aqui

# Configura la base de datos (elige UNA opción):

# Opción A: Usar DATABASE_URL (recomendado)
DATABASE_URL=${{MySQL.MYSQL_URL}}

# Opción B: Variables separadas (si prefieres)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

### 5. Generar APP_KEY

Ejecuta localmente en tu proyecto:
```bash
php artisan key:generate --show
```

Copia la clave generada y pégala en `APP_KEY` en Railway.

### 6. Configurar Build & Deploy

1. Ve a la pestaña **"Settings"** de tu servicio
2. En **"Build Command"** pon:
   ```bash
   bash ./build-app.sh
   ```
3. En **"Pre-Deploy Command"** pon:
   ```bash
   php artisan migrate --force
   ```
4. En **"Start Command"** pon:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

### 7. Configurar Servicios Adicionales

#### Servicio de Colas (Opcional)
Si usas jobs/colas:
1. **"New Service"** → **"GitHub Repo"**
2. Selecciona el mismo repositorio
3. **"Start Command"**: `bash ./run-worker.sh`

#### Servicio de Cron (Opcional)
Si usas tareas programadas:
1. **"New Service"** → **"GitHub Repo"**
2. Selecciona el mismo repositorio
3. **"Start Command"**: `bash ./run-cron.sh`

### 8. Configurar Dominio

1. Ve a la pestaña **"Settings"**
2. En **"Domains"** haz clic en **"Generate Domain"**
3. Copia el dominio generado
4. Actualiza `APP_URL` en las variables con este dominio

### 9. Verificar el Despliegue

1. Haz clic en el dominio generado
2. Verifica que la aplicación cargue correctamente
3. Revisa los logs en Railway si hay errores

## 🔧 Solución de Problemas

### Error de Permisos
Si ves errores de permisos:
```bash
# En build-app.sh ya está incluido
chmod -R 775 storage bootstrap/cache
```

### Error de Base de Datos
- Verifica que las variables de DB estén correctas
- Asegúrate de que el servicio MySQL esté funcionando
- Revisa que las migraciones se ejecuten correctamente

### Error de Assets
Si los assets no cargan:
- Verifica que el build de Vite se ejecute correctamente
- Revisa que `APP_URL` esté configurado correctamente

### Logs de Railway
Para ver logs en tiempo real:
1. Ve a tu servicio en Railway
2. Pestaña **"Deployments"**
3. Haz clic en el deployment más reciente
4. Revisa los logs de build y runtime

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs en Railway
2. Verifica que todas las variables estén configuradas
3. Asegúrate de que los scripts tengan permisos de ejecución

## ✅ Checklist Final

- [ ] Repositorio conectado a Railway
- [ ] Base de datos MySQL creada
- [ ] Variables de entorno configuradas
- [ ] APP_KEY generada y configurada
- [ ] Build y Pre-deploy commands configurados
- [ ] Dominio generado y configurado
- [ ] Aplicación funcionando correctamente
- [ ] Migraciones ejecutadas
- [ ] Assets cargando correctamente

¡Tu aplicación debería estar funcionando en Railway! 🎉
