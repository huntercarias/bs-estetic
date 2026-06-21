#!/bin/bash
# =============================================================================
#  SCRIPT DE DESPLIEGUE AUTOMATIZADO — bs-estetic.com
#  Uso: bash deploy.sh
#  Uso primera vez: bash deploy.sh --fresh
# =============================================================================
set -e

# ─── CONFIGURACIÓN ────────────────────────────────────────────────────────────
FTP_HOST="77.237.243.246"
FTP_USER="bsesteti"
FTP_PASS='(135B6S.a8ylwY'
REMOTE_APP="clinica-app"       # Directorio app fuera de public_html
REMOTE_PUBLIC="public_html"    # Raíz web
LOCAL_APP="/mnt/sdcard/Documents/laravelProyecto/clinica-Proyecto"
FRESH_DEPLOY=false

# ─── ARGUMENTOS ───────────────────────────────────────────────────────────────
for arg in "$@"; do
  case $arg in
    --fresh) FRESH_DEPLOY=true ;;
  esac
done

# ─── COLORES ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'
ok()   { echo -e "${GREEN}✔${NC} $1"; }
info() { echo -e "${BLUE}▸${NC} $1"; }
warn() { echo -e "${YELLOW}⚠${NC} $1"; }
fail() { echo -e "${RED}✘ ERROR:${NC} $1"; exit 1; }

# ─── VERIFICACIONES PREVIAS ───────────────────────────────────────────────────
check_requirements() {
  command -v lftp &>/dev/null || fail "lftp no instalado. Ejecuta: pkg install lftp"
  command -v php  &>/dev/null || fail "PHP no encontrado."
  [ -f "$LOCAL_APP/.env.production" ] || fail "Falta .env.production\n  Copia y configura: cp $LOCAL_APP/.env.production.example $LOCAL_APP/.env.production"
  ok "Requisitos verificados"
}

# ─── PREPARAR LARAVEL PARA PRODUCCIÓN ─────────────────────────────────────────
prepare_laravel() {
  info "Preparando Laravel para producción..."
  cd "$LOCAL_APP"
  php artisan config:clear  -q
  php artisan route:clear   -q
  php artisan view:clear    -q
  php artisan config:cache  -q
  php artisan route:cache   -q
  php artisan view:cache    -q
  ok "Laravel optimizado"
}

# ─── SUBIR VÍA FTP ────────────────────────────────────────────────────────────
deploy_via_ftp() {
  info "Conectando al servidor FTP $FTP_HOST..."

  # Flags de mirror según tipo de deploy
  if [ "$FRESH_DEPLOY" = true ]; then
    MIRROR_FLAGS="--reverse --verbose --parallel=4"
    info "Modo: DESPLIEGUE INICIAL (sube todo)"
  else
    MIRROR_FLAGS="--reverse --delete --verbose --parallel=4 --only-newer"
    info "Modo: ACTUALIZACIÓN (solo archivos cambiados)"
  fi

  # Preparar archivos temporales de seguridad ANTES de entrar a lftp
  cat > /tmp/storage_htaccess <<'HTEOF'
Options -Indexes
<FilesMatch "\.php$">
  Order allow,deny
  Deny from all
</FilesMatch>
HTEOF

  lftp -c "
set ftp:ssl-allow yes
set ssl:verify-certificate no
set net:timeout 60
set net:max-retries 5
set net:reconnect-interval-base 5
set mirror:parallel-transfer-count 4

open -u '$FTP_USER','$FTP_PASS' ftp://$FTP_HOST

mkdir -p $REMOTE_APP/storage/app/public
mkdir -p $REMOTE_APP/storage/framework/cache/data
mkdir -p $REMOTE_APP/storage/framework/sessions
mkdir -p $REMOTE_APP/storage/framework/views
mkdir -p $REMOTE_APP/storage/logs
mkdir -p $REMOTE_APP/bootstrap/cache
mkdir -p $REMOTE_APP/database

mirror $MIRROR_FLAGS \
  --exclude-rx '^\.git$' \
  --exclude-rx '^\.env(\..*)?$' \
  --exclude-rx '^node_modules$' \
  --exclude    'public' \
  --exclude-rx '^deploy(\.sh)?$' \
  --exclude-rx '^storage/logs/.*\.log$' \
  $LOCAL_APP/ $REMOTE_APP/

put $LOCAL_APP/.env.production -o $REMOTE_APP/.env

mirror $MIRROR_FLAGS \
  --exclude-rx '^adminer' \
  $LOCAL_APP/public/ $REMOTE_PUBLIC/

put $LOCAL_APP/deploy/index.production.php  -o $REMOTE_PUBLIC/index.php
put $LOCAL_APP/deploy/post-deploy.php       -o $REMOTE_PUBLIC/post-deploy.php
put $LOCAL_APP/deploy/htaccess.production   -o $REMOTE_PUBLIC/.htaccess
put $LOCAL_APP/deploy/user.ini.production   -o $REMOTE_PUBLIC/.user.ini
put $LOCAL_APP/deploy/app-htaccess.production -o $REMOTE_APP/.htaccess
put /tmp/storage_htaccess -o $REMOTE_APP/storage/app/public/.htaccess

bye
"
  ok "Archivos subidos correctamente"
}

# ─── LIMPIAR CACHÉ LARAVEL LOCAL ──────────────────────────────────────────────
cleanup_local_cache() {
  cd "$LOCAL_APP"
  php artisan config:clear -q
  php artisan route:clear  -q
  php artisan view:clear   -q
  ok "Caché local restaurada (modo dev)"
}

# ─── RESUMEN FINAL ────────────────────────────────────────────────────────────
print_summary() {
  echo ""
  echo -e "${GREEN}══════════════════════════════════════════${NC}"
  echo -e "${GREEN}  DEPLOY COMPLETADO ✔${NC}"
  echo -e "${GREEN}══════════════════════════════════════════${NC}"
  echo ""
  echo -e "  ${BLUE}URL:${NC}       https://77.237.243.246/~bsesteti/"
  echo ""

  if [ "$FRESH_DEPLOY" = true ]; then
    echo -e "  ${YELLOW}PASOS POST-DEPLOY (solo primera vez):${NC}"
    echo ""
    echo -e "  1. Abre en el navegador:"
    echo -e "     ${BLUE}https://77.237.243.246/~bsesteti/post-deploy.php?token=BS_DEPLOY_2025${NC}"
    echo ""
    echo -e "  2. Esto ejecutará migraciones y configurará storage."
    echo ""
    echo -e "  3. Elimina post-deploy.php después de usarlo:"
    echo -e "     Entra al cPanel File Manager y borra public_html/post-deploy.php"
    echo ""
  fi
  echo -e "${GREEN}══════════════════════════════════════════${NC}"
}

# ─── EJECUTAR ─────────────────────────────────────────────────────────────────
echo ""
echo -e "${BLUE}╔══════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  DEPLOY → bs-estetic.com                 ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════╝${NC}"
echo ""

check_requirements
prepare_laravel
deploy_via_ftp
cleanup_local_cache
print_summary
