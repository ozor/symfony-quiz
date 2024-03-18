# Получение курсов, кроскурсов ЦБ

## Требования:

Нужно сделать простую систему тестирования, поддерживающую вопросы с нечеткой логикой и возможностью выбора нескольких вариантов ответа.

- Пользователь должен иметь возможность пройти тест от начала до конца и в конце увидеть два списка - вопросы на которые он ответил верно и вопросы, где ответы содержали ошибки.
- Должна быть возможность пройти тест сколько угодно раз
- Каждый результат тестирования должен сохраняться в БД


## Реализация:

- PHP 8.2
- Symfony 7.0
- PostgreSQL 16
- Docker, Docker-compose
- Symfony Encore, jQuery, Bootstrap


## Установка:

1. Docker и Docker-compose должны быть установлены. 
2. Необходима поддержка команды `make` (в Linux ОС она "из коробки", в Windows нужно установить "make" из "Cygwin" или "Git Bash")
3. Процесс установки:
    - `git clone https://github.com/ozor/symfony-quiz.git`
    - `make init`

- После выполнения команды `make init` сервер будет запущен и приложение будет доступно по адресу: `http://localhost:8098`
- В дальнейшем, для остановки сервера, можно использовать команду: `make down`, для последующих запусков: `make up`


## Использование:

После установки и запуска докера приложение будет доступно по адресу: http://localhost:8098

Для начала тестирования нужно зарегистрироваться и войти в систему.

После установки будут доступны предустановленные данные
  - Тестовый пользователь:
    - Логин: `demo`
    - Пароль: `12345`
  - Данные для тестирования (вопросы и ответы)