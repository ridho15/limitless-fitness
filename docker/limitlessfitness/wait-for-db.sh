#!/bin/bash
set -e

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USERNAME:-root}"
DB_PASS="${DB_PASSWORD:-}"
TIMEOUT="${DB_WAIT_TIMEOUT:-60}"

echo "[wait-for-db] Waiting for ${DB_HOST}:${DB_PORT} (timeout ${TIMEOUT}s)"

count=0
while true; do
  if [ -n "$DB_PASS" ]; then
    echo "[wait-for-db] Attempt $count: mysqladmin ping to $DB_HOST:$DB_PORT as $DB_USER (with password)"
    mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" -u"$DB_USER" -p"$DB_PASS" --skip-ssl-verify-server-cert --connect-timeout=5 --silent >/dev/null 2>&1
    rc=$?
  else
    echo "[wait-for-db] Attempt $count: mysqladmin ping to $DB_HOST:$DB_PORT as $DB_USER (no password)"
    mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" -u"$DB_USER" --skip-ssl-verify-server-cert --connect-timeout=5 --silent >/dev/null 2>&1
    rc=$?
  fi

  if [ "$rc" -eq 0 ]; then
    echo "[wait-for-db] Database is ready (attempt $count)"
    break
  else
    echo "[wait-for-db] mysqladmin returned $rc"
  fi

  sleep 1
  count=$((count+1))
  if [ "$count" -ge "$TIMEOUT" ]; then
    echo "[wait-for-db] Timeout after ${TIMEOUT}s waiting for DB ${DB_HOST}:${DB_PORT}"
    exit 1
  fi
done

echo "[wait-for-db] Database is ready"
