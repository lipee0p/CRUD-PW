# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala e ativa a extensão PDO MySQL que é necessária para a conexão de banco de dados no PHP
RUN docker-php-ext-install pdo pdo_mysql

# Habilita o mod_rewrite do Apache caso seja necessário reescrever URLs no futuro
RUN a2enmod rewrite

# Ajusta as permissões dos arquivos do projeto no container Apache
RUN chown -R www-data:www-data /var/www/html
