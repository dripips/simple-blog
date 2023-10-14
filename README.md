# Простой блог на PHP и MySQL

Простой блог, созданный на PHP и использующий MySQL в качестве базы данных. В этом блоге пользователи могут создавать, редактировать и удалять свои посты, а также просматривать посты других пользователей и оставлять комментарии.

## Установка

1. **Склонируйте репозиторий**:

   ```bash
   git clone https://github.com/dripips/simple-blog.git
   ```

2. **Создайте базу данных**:

   - Создайте базу данных MySQL в вашем веб-сервере.
   - В файле `config/database.php` укажите данные для подключения к вашей базе данных:

   ```php
   $host = 'localhost';
   $dbname = 'your_database_name';
   $username = 'your_database_username';
   $password = 'your_database_password';
   ```

3. **Создайте базу данных MySQL:**
```SQL
CREATE DATABASE task_manager;
```
4. **Создайте следующие таблицы:**

```SQL
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_group INT NOT NULL DEFAULT 2
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE post_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE posts
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE post_ratings
ADD CONSTRAINT fk_post_id
FOREIGN KEY (post_id) REFERENCES posts(id);

ALTER TABLE post_ratings
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE comments
ADD CONSTRAINT fk_post_id
FOREIGN KEY (post_id) REFERENCES posts(id);

ALTER TABLE comments
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(id);

```
5. **Запустите веб-сервер**:

   - Запустите веб-сервер (например, Apache) и убедитесь, что PHP включено.

6. **Запустите ваше приложение**:

   - Откройте веб-браузер и перейдите по адресу `http://localhost/simple-blog/index.php` (замените `localhost` на адрес вашего веб-сервера и порт по умолчанию).

## Использование

1. **Авторизация**:

   - Зарегистрируйтесь или войдите в систему существующими учетными данными. При первой регистрации измените ид группы на 1.

2. **Создание постов**:

   - После входа в систему вы можете создавать новые посты. Просто нажмите "Создать пост" на панели навигации.

3. **Редактирование и удаление постов**:

   - Посты можно редактировать и удалять в разделе "Мои посты".

4. **Просмотр постов других пользователей**:

   - Перейдите на главную страницу для просмотра всех опубликованных постов.

5. **Оставление комментариев**:

   - После просмотра поста другого пользователя вы можете оставить комментарий под постом.

## Технологии

- PHP
- MySQL
- HTML/CSS
- JavaScript/jQuery

## Дополнение

Можно добавить рейтинг к записям.

## Лицензия

Этот проект распространяется под лицензией MIT. Подробнее ознакомьтесь с файлом [LICENSE](LICENSE).
