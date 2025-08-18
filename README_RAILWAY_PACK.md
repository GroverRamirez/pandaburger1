# 🚀 Paquete Railway para PandaBurger

Este paquete contiene todo lo necesario para desplegar tu aplicación Laravel + Vue.js en Railway sin complicaciones.

## 📦 Qué incluye el paquete

- **`README_DEPLOY.md`** → Guía completa paso a paso
- **`build-app.sh`** → Script de build automático
- **`run-cron.sh`** → Servicio de tareas programadas
- **`run-worker.sh`** → Servicio de colas
- **`env.railway.example`** → Plantilla de variables de entorno
- **`railway.json`** → Configuración de Railway
- **`setup-permissions.sh`** → Configuración de permisos

## ⚡ Uso Rápido

### 1. Preparar el repositorio
```bash
# Dar permisos de ejecución a los scripts
chmod +x *.sh

# Hacer commit y push
git add .
git commit -m "Add Railway deployment package"
git push origin main
```

### 2. Crear proyecto en Railway
1. Ve a [Railway Dashboard](https://railway.app/dashboard)
2. **"New Project"** → **"Deploy from GitHub repo"**
3. Selecciona tu repo `GroverRamirez/pandaburger1`
4. Haz clic en **"Deploy Now"**

### 3. Configurar MySQL
1. En tu proyecto Railway → **"New"** → **"Database"** → **"MySQL"**
2. Espera a que se cree la base de datos

### 4. Configurar Variables
1. Ve a **"Variables"** → **"Raw Editor"**
2. Copia el contenido de `env.railway.example`
3. **IMPORTANTE**: Modifica:
   - `APP_URL` = tu dominio Railway
   - `APP_KEY` = genera con `php artisan key:generate --show`

### 5. Configurar Build & Deploy
- **Build Command**: `bash ./build-app.sh`
- **Pre-Deploy Command**: `php artisan migrate --force`
- **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

### 6. Generar dominio
1. **"Settings"** → **"Domains"** → **"Generate Domain"**
2. Actualiza `APP_URL` con el nuevo dominio

## 🔧 Servicios Adicionales (Opcionales)

### Servicio de Colas
Si usas jobs/colas:
- **"New Service"** → **"GitHub Repo"**
- **Start Command**: `bash ./run-worker.sh`

### Servicio de Cron
Si usas tareas programadas:
- **"New Service"** → **"GitHub Repo"**
- **Start Command**: `bash ./run-cron.sh`

## 📋 Checklist de Verificación

- [ ] Scripts con permisos de ejecución
- [ ] Repositorio conectado a Railway
- [ ] Base de datos MySQL creada
- [ ] Variables de entorno configuradas
- [ ] APP_KEY generada y configurada
- [ ] Build y Pre-deploy commands configurados
- [ ] Dominio generado y configurado
- [ ] Aplicación funcionando correctamente

## 🆘 Solución de Problemas

### Error de permisos
```bash
# Ejecutar localmente
chmod +x *.sh
git add .
git commit -m "Fix script permissions"
git push
```

### Error de build
- Revisa los logs en Railway
- Verifica que todas las dependencias estén en `composer.json` y `package.json`

### Error de base de datos
- Verifica que las variables de DB estén correctas
- Asegúrate de que el servicio MySQL esté funcionando

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs en Railway
2. Verifica la guía completa en `README_DEPLOY.md`
3. Asegúrate de que todos los pasos del checklist estén completados

¡Tu aplicación debería estar funcionando en Railway! 🎉
