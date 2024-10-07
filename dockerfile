# Use a imagem base do PHP com Apache
FROM php:8.2-apache

# Instale as dependências necessárias e a extensão IMAP
RUN apt-get update && \
    apt-get install -y \
    libc-client-dev libkrb5-dev \
    && apt-get clean

# Baixe e instale a extensão IMAP manualmente
RUN docker-php-source extract \
    && cd /usr/src/php/ext/imap \
    && phpize \
    && ./configure --with-imap --with-imap-ssl --with-kerberos \
    && make \
    && make install \
    && docker-php-ext-enable imap \
    && docker-php-source delete

# Copie seu código para o contêiner
COPY ./src /var/www/html/

COPY ./index.php /var/www/html/

# Configure o Apache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Exponha a porta 80
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
