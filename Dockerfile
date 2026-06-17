FROM php:8.2-alpine

WORKDIR /app

COPY . /app

CMD ["php", "Jogo.php"]