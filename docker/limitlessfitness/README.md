Docker setup for Dailydose-Pilates

✅ Database is now a dedicated `mysql` service in `docker-compose.yml` (recommended for development).

Quick start (from project root):

1. Build and start:
   docker compose up --build -d

2. Environment variables (in `docker-compose.yml` or a `.env` file):
   - DB_HOST (default in compose: `mysql`)
   - DB_PORT (default: 3306)
   - DB_DATABASE
   - DB_USERNAME
   - DB_PASSWORD
   - MYSQL_ROOT_PASSWORD (for the `mysql` service)

3. Visit the app at: http://localhost:8080
   MySQL is accessible on host loopback port 3307 (host: 127.0.0.1:3307) — it is not exposed publicly. To connect remotely, create an SSH tunnel to the host and forward the port.

   Example (from your local machine):
   - ssh -L 3307:127.0.0.1:3307 user@server.example.com -N
   - Then connect locally to: mysql -h 127.0.0.1 -P 3307 -u daily -p

   **Security note:** this setup prevents public exposure; ensure you have an SSH account on the host, use strong DB passwords, and restrict SSH access with keys/firewall. If you still want public exposure, change the port mapping to `"3307:3306"` (not recommended).

Notes:
- The Docker image uses PHP 8.2 (compatible with composer requirement `php: ^8.1`).
- On first startup the `mysql` service will initialize the database and the app container will attempt a `composer install` if `vendor/` is missing.
