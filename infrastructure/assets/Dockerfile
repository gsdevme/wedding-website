FROM nginx:latest

RUN mkdir -p /usr/share/nginx/html/assets

COPY ./public/assets /usr/share/nginx/html/assets/
COPY ./infrastructure/assets/vhost.conf /etc/nginx/conf.d/vhost.conf

#USER nginx
EXPOSE 8080
