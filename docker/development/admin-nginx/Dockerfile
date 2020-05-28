FROM nginx:1.17-alpine

COPY ./conf.d /etc/nginx/conf.d

COPY ./hosts/hosts /etc/hosts

RUN apk add nano

WORKDIR /app/admin