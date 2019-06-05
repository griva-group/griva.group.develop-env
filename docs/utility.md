# [GGDDEnv] Полезные дополнения

## Создание контейнера с phpMyAdmin панелью

```bash
docker run \
    -p 8081:80 \
    -e PMA_ARBITRARY=1 \
    --network ggenv.network \
    --name ggenv.pma phpmyadmin/phpmyadmin
```

И набор полезных комманд после его создания

```bash
# Запуск уже созданного контейнера с сервером nginx
docker start ggenv.pma

# Принудительная остановка сервера nginx
docker stop -t 5 ggenv.pma

# Перезагрузка сервера
docker restart ggenv.pma
```
