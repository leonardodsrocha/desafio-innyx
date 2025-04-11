# Use a imagem do PHP como base
FROM php:8.1-fpm

# Instale as extensões PHP necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Instale as ferramentas e dependências necessárias
RUN apt-get update && apt-get install -y git zip unzip

# Configure o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do Laravel para o contêiner
COPY ./innyx_back .

# Instale as dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/imagens

# Exponha a porta 9000 para o servidor PHP-FPM
EXPOSE 9000

# Inicie o servidor PHP-FPM
CMD ["php-fpm"]
