#!/bin/bash
set -e

echo "------------------------------------------"
echo "🚀 Iniciando proceso de despliegue"
echo "------------------------------------------"

# 1. Preparar el entorno para la actualización
# Forzamos que package-lock.json vuelva a su estado original de Git 
# para evitar errores de "local changes overwritten by merge"
echo "🧹 Limpiando cambios temporales en archivos de bloqueo..."
git checkout package-lock.json composer.lock || true

# Poner la aplicación en modo mantenimiento
php artisan down --render="errors::503" --refresh=15 || true

echo "📥 Extrayendo última versión de Git..."
git pull origin main

echo "📦 Instalando dependencias de PHP (Composer)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📦 Instalando dependencias de JS (NPM)..."
npm ci || npm install

echo "⚡ Compilando Assets con Vite..."
npm run build

echo "🧹 Limpiando y generando caché de Laravel..."
php artisan optimize
php artisan view:cache
php artisan event:cache

echo "🗄️ Ejecutando migraciones de base de datos..."
php artisan migrate --force

# 2. Volver a poner la aplicación en línea
php artisan up

echo "------------------------------------------"
echo "✅ ¡Despliegue completado con éxito!"
echo "------------------------------------------"