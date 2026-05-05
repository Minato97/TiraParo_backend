# ── Dockerfile de producción para Railway ──────────────────────────────────
# Combina PHP 8.3-FPM + nginx en un solo contenedor gestionado por Supervisor.
# Railway no soporta docker-compose — todo debe vivir en un solo proceso raíz.

FROM php:8.3-fpm

# ── Dependencias del sistema ────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ── Extensiones PHP ─────────────────────────────────────────────────────────
RUN docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd zip

# ── Redis (extensión PHP) ────────────────────────────────────────────────────
RUN pecl install redis && docker-php-ext-enable redis

# ── Composer ────────────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ── Código de la aplicación ──────────────────────────────────────────────────
WORKDIR /var/www
COPY . .

# ── Dependencias PHP (sin dev, optimizado para producción) ──────────────────
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ── Permisos de Laravel ──────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ── Configuración nginx ──────────────────────────────────────────────────────
COPY docker/nginx/railway.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default.bak

# ── Configuración Supervisor (orquesta nginx + php-fpm) ─────────────────────
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ── Script de inicio ─────────────────────────────────────────────────────────
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Railway asigna el puerto dinámicamente via $PORT (default 80)
EXPOSE 80

CMD ["/start.sh"]
