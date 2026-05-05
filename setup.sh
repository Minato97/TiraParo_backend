#!/bin/bash

echo "🌱 TiraParo Backend — Setup"
echo "=============================="

# 1. Copiar .env si no existe
if [ ! -f .env ]; then
    echo "📄 Creando archivo .env..."
    cp .env.example .env
else
    echo "⚠️  .env ya existe. Se omite copia."
fi

# 2. Levantar contenedores
echo "🐳 Levantando contenedores Docker..."
docker compose up -d --build

# Esperar a que MySQL esté listo (hasta 30 segundos)
echo "⏳ Esperando a que MySQL esté disponible..."
for i in {1..15}; do
    if docker compose exec db mysqladmin ping -h"localhost" --silent 2>/dev/null; then
        echo "✅ MySQL listo."
        break
    fi
    sleep 2
done

# 3. Composer install
echo "📦 Instalando dependencias PHP..."
docker compose exec app composer install --no-interaction --prefer-dist

# 4. Generar APP_KEY
echo "🔑 Generando APP_KEY..."
docker compose exec app php artisan key:generate --force

# 5. Crear enlace simbólico para storage (fotos de escaneos)
echo "🔗 Creando storage link..."
docker compose exec app php artisan storage:link

# 6. Migraciones + Seeders
echo "🗄️  Ejecutando migraciones y seeders..."
docker compose exec app php artisan migrate:fresh --seed --force

echo ""
echo "✅ TiraParo Backend listo."
echo ""
echo "📍 URLs disponibles:"
echo "   API:         http://localhost:8000/api"
echo "   phpMyAdmin:  http://localhost:8080"
echo ""
echo "🚀 Para exponer con ngrok:"
echo "   ngrok http 8000"
echo ""
echo "📱 Endpoints principales:"
echo "   POST /api/auth/register"
echo "   POST /api/auth/login"
echo "   GET  /api/categorias"
echo "   GET  /api/centros?latitud=19.41&longitud=-99.17"
echo "   POST /api/escaneos"
echo "   POST /api/escaneos/{id}/evidencia"
echo "   GET  /api/perfil/impacto"
echo "   GET  /api/comunidad/leaderboard"
echo "   GET  /api/mascota"
