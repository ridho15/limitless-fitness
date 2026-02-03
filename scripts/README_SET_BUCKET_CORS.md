# Apply bucket CORS (run on storage host / admin)

This short runbook helps an administrator apply CORS to the `limitlessfitness` bucket on the `storage.playgo.id` endpoint using the MinIO client (`mc`).

1) Prepare `cors.json` (already included in `scripts/cors.json`):

```json
[
  {
    "AllowedOrigins": ["https://your.app.domain"],
    "AllowedMethods": ["GET", "PUT", "POST", "DELETE", "HEAD", "OPTIONS"],
    "AllowedHeaders": ["*"],
    "ExposeHeaders": ["ETag"],
    "MaxAgeSeconds": 3600
  }
]
```

2) Run the helper script (replace values):

```bash
chmod +x scripts/apply_bucket_cors_with_mc.sh
./scripts/apply_bucket_cors_with_mc.sh \
  --endpoint https://storage.playgo.id \
  --alias playgo \
  --bucket limitlessfitness \
  --access-key <ACCESS_KEY> \
  --secret <SECRET_KEY>
```

3) Verify preflight from a host (replace origin):

```bash
curl -i -X OPTIONS "https://storage.playgo.id/limitlessfitness/anything" \
  -H "Origin: https://your.app.domain" \
  -H "Access-Control-Request-Method: PUT" \
  -H "Access-Control-Request-Headers: content-type"
```

Expected: `HTTP/1.1 200 OK` and `Access-Control-Allow-Origin` header pointing to the origin.

Troubleshooting
- If the `mc` commands succeed but browser uploads still get CORS errors (or you see `502` from the gateway), check the gateway/proxy logs (nginx/openresty) and MinIO logs.
- If `PutBucketCors` via SDK returns 502, run the `mc` commands locally on the storage host or inside the same network; the gateway may block administrative calls.
- If you can provide admin access (or run the script on the storage host), I can run verification and test a real Livewire signed PUT flow.

Security note
- Do not commit real credentials. Use environment variables or pass the secrets as parameters only when running the script on the admin machine.
