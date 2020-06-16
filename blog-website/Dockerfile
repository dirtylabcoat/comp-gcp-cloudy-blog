FROM composer AS composer
COPY . /app
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress

FROM trafex/alpine-nginx-php7
ARG SECRET_DIR=/etc/nginx/secret
ARG GA_CREDENTIALS
ENV GOOGLE_APPLICATION_CREDENTIALS=${SECRET_DIR}/credentials.json
USER root
RUN mkdir -p ${SECRET_DIR}
RUN echo ${GA_CREDENTIALS} > ${SECRET_DIR}/credentials.json
COPY --chown=nginx --from=composer /app /var/www/html
USER nobody
