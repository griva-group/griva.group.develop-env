# [GGDDEnv] Добавление нового проекта

## Добавление нового проекта

Все контейнеры, кроме http-сервера описываются в папке виртуального хоста. В
тестовом проекте `~sample.vhost` воссоздана базовая файловая структура. Можно
менять состав и настройки по желанию и необходимости. Ниже я опишу базовую
настройку PHP+MySQL.

Лучше скопировать папку тестового проекта, что бы избежать ошибок. Название
новой папки дается на основе домена нового хоста. После - заменить навзание
тестовго проекта `sample.vhost` на домен новго в файлу `docker-compose.yaml`

```yaml
services:
  php:
    container_name: php.%project_name%
      environment:
      - VHOST_NAME=%project_name%
  sql:
    container_name: php.%project_name%.sql
```

### Получение ssl-сертификатов

!!! В данный момент получение и настройка описаны только для http-сервера nginx

В базовой настройке http-серверов по умолчанию зашиты кофигурационные файлы для
правильно обработки acme-challenge, поэтому никаких особых действий не требутся.

Для запуска сертифицирующего контейнера необходимо перейти в корневую папку
данной сборки и выполнить следующую команду. После запуска контейнера следуйте
его инструкциям.

```bash
docker run -it --rm --name certbot \
    -v ggenv.cert.storage:/etc/letsencrypt \
    -v $(pwd)/projects:/var/lib/letsencrypt \
    certbot/certbot certonly
```

После, необходимо обновить базовый конфигурационный файл, и привести от такого
вида:

```smartyconfig
server {
    listen 80;
    listen [::]:80;
    
    #...
}
```

К такому:

```smartyconfig
server {
    listen [::]:443 ssl http2 ipv6only=on;
    listen 443 ssl http2;
    
    #...
    
    ssl_certificate /etc/letsencrypt/live/${VHOST_NAME}/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/${VHOST_NAME}/privkey.pem;

}

server {
    if ($$host = ${VHOST_NAME}) {
        return 301 https://$$host$$request_uri;
    } # managed by Certbot


    listen 80;
    listen [::]:80;

    server_name ${VHOST_NAME};
    include nginx.conf.d/letsencrypt.conf;
    return 404; # managed by Certbot
}
```

### Управление контейнерами

Управление контейнерами происходит с помощью следующих команд, которые должны
исполнятся в папке проекта (где находится `docker-compose.yaml` файл).

```bash
# Запуск виртуальнго хоста
docker-compose up -d
docker exec -ti ggenv.nginx nginx -s reload

# Остановка виртуального хоста
docker-compose up -d
docker exec -ti ggenv.nginx nginx -s reload

# Вход в интерактивную оболочку (например: php)
docker exec -ti php.%project_name$ /bin/bash
```

### Создание виртуального раздела

Он необходим только для оптимизации файловой структуры контейнеров и приводит к
ускорению файловых операций, что безусловно влияет на производительность сайта
в целом.

Создать раздел можно следующей командой. Желательно, что бы в названии
содержался домен виртуального хоста, тип и версия сервера БД.

```bash
docker volume create sql56.sample.vhost
```

А для подключения в `docker-compose.yaml` необходимо указать примерно такие
строчки:

```yaml
services:
  # ...
  sql:
    # ...
    volumes:
      - sql56.sample.vhost:/var/lib/mysql:cached
  # ...
volumes:
  sql56.sample.vhost:
    external: true
```
