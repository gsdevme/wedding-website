FROM docker.io/nginxinc/nginx-unprivileged:1.27.5-alpine

COPY infrastructure/nginx.conf /etc/nginx/conf.d/default.conf
COPY site/ /usr/share/nginx/html/

EXPOSE 8080
