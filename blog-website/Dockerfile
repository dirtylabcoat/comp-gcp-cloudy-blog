FROM webdevops/php-nginx:7.4-alpine
COPY . /app
ARG GA_CREDENTIALS
RUN echo ${GA_CREDENTIALS} > /app/credentials.json
RUN cd /app && composer install
ENV GOOGLE_APPLICATION_CREDENTIALS=/app/credentials.json
