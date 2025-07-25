FROM php:8.3-alpine

# Install system & PHP dependencies
RUN apk update && apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    ca-certificates \
    libxml2-dev \
    libxslt-dev \
    oniguruma-dev \
    zsh \
    && docker-php-ext-install xsl

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create non-root user
ARG USERNAME=devuser
ARG USER_UID=1000
ARG USER_GID=${USER_UID}
RUN addgroup -g ${USER_GID} ${USERNAME} && \
    adduser -D -u ${USER_UID} -G ${USERNAME} -s /bin/zsh ${USERNAME}

USER ${USERNAME}
WORKDIR /workspaces/symfony