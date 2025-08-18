# 🔑 Generar APP_KEY para Railway

## ¿Qué es APP_KEY?

La `APP_KEY` es una clave de encriptación que Laravel usa para:
- Encriptar cookies y sesiones
- Firmar URLs
- Encriptar datos sensibles

## ⚠️ IMPORTANTE

**NUNCA uses la misma APP_KEY de desarrollo en producción.**

## 🔧 Cómo generar una nueva APP_KEY

### Opción 1: Usando Artisan (Recomendado)

```bash
# En tu proyecto local
php artisan key:generate --show
```

Este comando mostrará algo como:
```
base64:AbCdEfGhIjKlMnOpQrStUvWxYz1234567890=
```

### Opción 2: Generar manualmente

Si no tienes PHP instalado localmente, puedes generar una clave manualmente:

1. Ve a: https://laravel-key-generator.herokuapp.com/
2. Haz clic en "Generate Key"
3. Copia la clave generada

### Opción 3: Usando PHP online

```php
<?php
echo 'base64:' . base64_encode(random_bytes(32));
?>
```

## 📝 Configurar en Railway

1. Ve a tu proyecto en Railway
2. Pestaña **"Variables"**
3. Busca `APP_KEY`
4. Reemplaza el valor con tu nueva clave
5. Guarda los cambios

## 🔍 Verificar que funciona

Después de configurar la APP_KEY:

1. Ve a tu aplicación en Railway
2. Si ves errores de encriptación, la APP_KEY no está configurada correctamente
3. Revisa los logs en Railway para más detalles

## 🚨 Problemas comunes

### Error: "No application encryption key has been specified"

- La APP_KEY no está configurada
- La APP_KEY está mal formateada

### Error: "The MAC is invalid"

- La APP_KEY cambió después de que se crearon las sesiones
- Necesitas limpiar las sesiones existentes

## ✅ Checklist

- [ ] Generé una nueva APP_KEY
- [ ] La APP_KEY empieza con `base64:`
- [ ] Configuré la APP_KEY en Railway
- [ ] La aplicación funciona sin errores de encriptación

## 📞 Si tienes problemas

1. Verifica que la APP_KEY esté correctamente formateada
2. Asegúrate de que empiece con `base64:`
3. Revisa los logs en Railway
4. Limpia las cachés si es necesario
