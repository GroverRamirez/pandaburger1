# 🚀 Inicio Rápido - Railway Deployment

## 📦 Tu paquete está listo

He creado un paquete completo para desplegar tu aplicación Laravel + Vue.js en Railway. Incluye:

### Archivos principales:
- ✅ `README_DEPLOY.md` - Guía completa paso a paso
- ✅ `README_RAILWAY_PACK.md` - Guía rápida
- ✅ `build-app.sh` - Script de build automático
- ✅ `run-cron.sh` - Servicio de tareas programadas
- ✅ `run-worker.sh` - Servicio de colas
- ✅ `env.railway.example` - Plantilla de variables
- ✅ `railway.json` - Configuración de Railway
- ✅ `GENERATE_APP_KEY.md` - Instrucciones para APP_KEY

## ⚡ Pasos para desplegar

### 1. Preparar repositorio
```bash
# Verificar archivos (Windows)
.\check-files.bat

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

## 🔧 Servicios adicionales (opcionales)

### Colas (si usas jobs)
- **"New Service"** → **"GitHub Repo"**
- **Start Command**: `bash ./run-worker.sh`

### Cron (si usas tareas programadas)
- **"New Service"** → **"GitHub Repo"**
- **Start Command**: `bash ./run-cron.sh`

## 📋 Checklist final

- [ ] Repositorio conectado a Railway
- [ ] Base de datos MySQL creada
- [ ] Variables de entorno configuradas
- [ ] APP_KEY generada y configurada
- [ ] Build y Pre-deploy commands configurados
- [ ] Dominio generado y configurado
- [ ] Aplicación funcionando correctamente

## 📖 Documentación

- **Guía rápida**: `README_RAILWAY_PACK.md`
- **Guía completa**: `README_DEPLOY.md`
- **APP_KEY**: `GENERATE_APP_KEY.md`

## 🆘 Si tienes problemas

1. Revisa los logs en Railway
2. Verifica que todas las variables estén configuradas
3. Asegúrate de que la APP_KEY esté correcta
4. Consulta la guía completa en `README_DEPLOY.md`

¡Tu aplicación debería estar funcionando en Railway! 🎉
