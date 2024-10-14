FROM php:7.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz \
        unzip\
        libgmp-dev\
    && docker-php-ext-install \
    mbstring zip gd bcmath exif \
    iconv fileinfo mysqli pdo pdo_mysql pcntl opcache

RUN docker-php-ext-configure bcmath --enable-bcmath
RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-configure mbstring --enable-mbstring
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql
RUN docker-php-ext-configure gd  \
        --with-png-dir=/usr/include/\
        --with-jpeg-dir=/usr/include/\
        --with-freetype-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install gmp
RUN docker-php-ext-enable gd
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*



# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --1

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
#ADD ./../website /var/www/website
#ADD ./../eClass /var/www/eClass

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

