# Comments tree

### Требования


* php 7.1
* composer
* mysql


### Развертывание проекта

Откройте окно терминала и перейдите в директорию, в которой хотите разместить проект.
Скопируйте файлы с git репозитория и перейдите в папку проекта

```sh
$ git clone https://github.com/vladimir-tender/solar
$ cd solar
```
Создайте базу данных
```sh
$ mysql -uroot -p
$ create database solar
```

Создайте файл конфигурации .env, в котором укажите подключение к БД (DB_HOST, DB_PORT, DB_DATABASE, DA_USERNAME, DB_PASSWORD)
```sh
$ cp .env.example .env
```
Сгенерируйте ключ приложения
```sh
$ php artisan key:generate
```
Загрузите миграции
```sh
$ php artisan migrate
```

Загрузите первичные данные
```sh
$ php artisan db:seed
```
Запустите встроенный сервер Laravel
```sh
$ php artisan serve
```

В браузере перейдите по локальному адресу http://127.0.0.1:8000/comments

### Ошибка ext-mbstring
Решение
```sh
$ sudo apt-get install php7.1-mbstring
```
