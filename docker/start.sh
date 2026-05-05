#!/bin/bash
set -e

echo "🚀 TiraParo — Iniciando servidor de producción..."

# ── Generar APP_KEY si no existe ─────────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY no definida — generando una nueva..."
    php artisan key:generate --force
fi

# ── Cachear configuración para producción (más rápido) ───────────────────────
echo "📦 Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Enlace simbólico de storage ───────────────────────────────────────────────
echo "🔗 Creando enlace de storage..."
php artisan storage:link --force 2>/dev/null || true

# ── Esperar a que MySQL esté listo ────────────────────────────────────────────
echo "⏳ Esperando conexión a MySQL..."
for i in {1..30}; do
    php artisan db:monitor 2>/dev/null && break
    echo "   Intento $i/30 — esperando 2s..."
    sleep 2
done

# ── Migraciones ───────────────────────────────────────────────────────────────
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# ── Seeders (solo si la tabla users está vacía) ────────────────────────────────
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "🌱 Ejecutando seeders (primera vez)..."
    php artisan db:seed --force
else
    echo "✅ Base de datos ya tiene datos — omitiendo seeders."
fi

# ── Iniciar Supervisor (nginx + php-fpm) ─────────────────────────────────────
echo "🟢 Servidor listo. Iniciando nginx + php-fpm..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
