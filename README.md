## Setup Backend (Docker)

### Requirements
- Docker
- Docker Compose

### Setup
```bash
git clone <repo>
cd shop-be
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app bash -lc "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
