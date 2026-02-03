#!/usr/bin/env bash
set -euo pipefail

# apply_bucket_cors_with_mc.sh
# Usage: ./apply_bucket_cors_with_mc.sh --endpoint https://storage.playgo.id --alias playgo --bucket limitlessfitness --access-key minio --secret minio123 --cors-file ./scripts/cors.json

print_usage(){
  cat <<EOF
Usage: $0 --endpoint <S3_ENDPOINT> --alias <MC_ALIAS> --bucket <BUCKET> --access-key <ACCESS_KEY> --secret <SECRET_KEY> [--cors-file <PATH_TO_CORS_JSON>] [--insecure]

This script requires the MinIO client (mc) to be installed and reachable in PATH.
It will set an alias, apply the CORS file and show the resulting CORS configuration.
EOF
}

ENDPOINT=""
ALIAS="playgo"
BUCKET=""
ACCESS_KEY=""
SECRET_KEY=""
CORS_FILE="$(dirname "$0")/cors.json"
INSECURE=""

while [[ $# -gt 0 ]]; do
  case "$1" in
    --endpoint) ENDPOINT="$2"; shift 2;;
    --alias) ALIAS="$2"; shift 2;;
    --bucket) BUCKET="$2"; shift 2;;
    --access-key) ACCESS_KEY="$2"; shift 2;;
    --secret) SECRET_KEY="$2"; shift 2;;
    --cors-file) CORS_FILE="$2"; shift 2;;
    --insecure) INSECURE="--insecure"; shift 1;;
    -h|--help) print_usage; exit 0;;
    *) echo "Unknown arg: $1"; print_usage; exit 1;;
  esac
done

if [[ -z "$ENDPOINT" || -z "$BUCKET" || -z "$ACCESS_KEY" || -z "$SECRET_KEY" ]]; then
  echo "Missing required args." >&2
  print_usage
  exit 2
fi

if ! command -v mc >/dev/null 2>&1; then
  echo "mc (MinIO client) not found in PATH. Install it: https://min.io/docs/misc/installation/
" >&2
  exit 3
fi

# Configure alias
echo "Setting mc alias '${ALIAS}' -> ${ENDPOINT}"
mc alias set "${ALIAS}" "${ENDPOINT}" "${ACCESS_KEY}" "${SECRET_KEY}" --api S3v4 ${INSECURE}

# Apply CORS
echo "Applying CORS from ${CORS_FILE} to ${ALIAS}/${BUCKET}"
mc cors set "${ALIAS}/${BUCKET}" "${CORS_FILE}"

# Show resulting CORS
echo "CORS for ${ALIAS}/${BUCKET}:"
mc cors get "${ALIAS}/${BUCKET}"

# Show verification curl example
cat <<EOF

Done. To verify from any host, try a preflight OPTIONS request (replace Origin):

curl -i -X OPTIONS "${ENDPOINT}/${BUCKET}/any" \
  -H "Origin: https://your.app.domain" \
  -H "Access-Control-Request-Method: PUT" \
  -H "Access-Control-Request-Headers: content-type"

If you still see 502 from the gateway, run this script on the storage host (or inside the storage network) and check gateway/proxy logs.

EOF
