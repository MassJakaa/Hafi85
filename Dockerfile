FROM php:8.2-apache

# Ensure only mpm_prefork is loaded to prevent AH00534 conflict
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Setup working directory
WORKDIR /var/www/html

# Copy source code
COPY . /var/www/html/

# Enable .htaccess AllowOverride in Apache
RUN echo '<Directory /var/www/html/>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/override.conf \
    && a2enconf override

# Ensure uploads directory is writable
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

# Dynamic port binding entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
