Использовать Swagger для ведения документации - правильно, но муторно

Я использовал библиотеку zircote/swagger-php из композера для генерации swagger.json. Основная проблема - сложный и неудобный интерфейс для настройки отправляемых данных. А если библиотеку не использовать, то надо редактировать сам swagger.json на 6к строк...

Еще проблема библиотеки, что она достаточно многословная. И описание самых простых функций ведет к тоннам строк кода... Но зато описать можно все же достаточно точно, хоть и очень неудобно. А особенно неудобно поддерживать актуальность потому что лень)

Зато есть поддержка на гите. А если ее нет, то можно достаточно просто развернуть с помощью докера образ, который будет отображать тебе сваггер

### Для запуска swagger ui

```shell
wsl docker run -p 80:8080 -e SWAGGER_JSON=/foo/swagger.json -v ./:/foo swaggerapi/swagger-ui
```

Подробнее про то, как работает библиотека php смотри в hard/PHP