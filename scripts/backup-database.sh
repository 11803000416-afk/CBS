#!/bin/bash

#############################################
# CBS Database Backup Script
# Purpose: Automated daily database backups with retention
# Usage: ./backup-database.sh or add to crontab
#############################################

set -e

# Configuration
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USER:-root}"
DB_PASSWORD="${DB_PASSWORD}"
DB_NAME="${DB_NAME:-cbs}"
BACKUP_DIR="${BACKUP_DIR:-./storage/backups}"
RETENTION_DAYS="${RETENTION_DAYS:-30}"
LOG_FILE="${BACKUP_DIR}/backup.log"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

# Create backup filename with timestamp
TIMESTAMP=$(date +'%Y%m%d_%H%M%S')
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_backup_${TIMESTAMP}.sql"
BACKUP_FILE_GZ="${BACKUP_FILE}.gz"

log "Starting database backup..."
log "Database: $DB_NAME"
log "Host: $DB_HOST:$DB_PORT"
log "Backup file: $BACKUP_FILE_GZ"

# Create the backup
if [ -z "$DB_PASSWORD" ]; then
    # Without password
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" \
        --single-transaction \
        --quick \
        --lock-tables=false \
        "$DB_NAME" > "$BACKUP_FILE" 2>&1
else
    # With password
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" \
        --single-transaction \
        --quick \
        --lock-tables=false \
        "$DB_NAME" > "$BACKUP_FILE" 2>&1
fi

if [ $? -eq 0 ]; then
    # Compress the backup
    gzip "$BACKUP_FILE"
    
    if [ $? -eq 0 ]; then
        FILE_SIZE=$(du -h "$BACKUP_FILE_GZ" | cut -f1)
        success "Database backed up successfully"
        success "File: $BACKUP_FILE_GZ"
        success "Size: $FILE_SIZE"
    else
        error "Failed to compress backup file"
    fi
else
    error "Failed to create database backup"
fi

# Delete old backups based on retention policy
log "Cleaning up old backups (retention: $RETENTION_DAYS days)..."
find "$BACKUP_DIR" -name "${DB_NAME}_backup_*.sql.gz" -mtime +$RETENTION_DAYS -delete

if [ $? -eq 0 ]; then
    success "Old backups removed"
else
    warning "Could not remove some old backups"
fi

# Count remaining backups
BACKUP_COUNT=$(find "$BACKUP_DIR" -name "${DB_NAME}_backup_*.sql.gz" | wc -l)
log "Total backups stored: $BACKUP_COUNT"

# Optional: Upload to S3 or remote storage
if command -v aws &> /dev/null && [ -n "$AWS_S3_BUCKET" ]; then
    log "Uploading backup to S3..."
    aws s3 cp "$BACKUP_FILE_GZ" "s3://${AWS_S3_BUCKET}/backups/" || warning "Failed to upload to S3"
fi

log "Backup complete!"
