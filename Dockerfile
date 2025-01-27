FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    texlive \
    texlive-lang-polish \
    libicu-dev \
    && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/*
COPY . /app
WORKDIR /app

CMD ["php", "makeFV.php"]

