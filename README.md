# Tic Tac Toe API

## How to play

You can use simple `curl` or another http testing tool of your choice like `Postman` or `Insomnia`.
A complete testing suite with example game result is provided in `tests/GameTest.php`.

### Start a new game

```bash
curl 'http://localhost/game' -XPOST -H 'Accept: application/json'
```

### Make a move

```bash
curl 'http://localhost/game/move' -XPOST \
    -H 'Accept: application/json' -H 'Content-type: application/json' \
    -d '{ "game_id": 1, "player": 1, "coordinates": { "x": 0, "y": 0 } }'
```

## Setup with Sail (Docker)

### Generate a new `.env` from `.env.example`

```bash
cp .env.example .env
```

### Generate a new `APP_KEY` to set inside you `.env`

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    laravelsail/php81-composer:latest \
    php -r "echo bin2hex(random_bytes(16)) . \"\n\";"
```

### Install Composer dependencies

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

### Start Laravel + Mysql

```bash
vendor/bin/sail up -d
```

### Run migrations

```bash
vendor/bin/sail artisan migrate
```
