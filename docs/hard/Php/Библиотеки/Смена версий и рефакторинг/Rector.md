Rector используется как статический анализатор, который может еще и версию языка поднять, и ошибки сам исправить. Работает вполне стабильно, но конечно за ним прям надо перепроверять. Иногда пишет лажу и ломает код

[Ссылка на сорцы](https://github.com/rectorphp/rector)

[Настройки](rector.php)

Для просмотра списка изменений

```shell
php vendor/bin/rector process --dry-run  
```

Для применения изменений

```shell
php vendor/bin/rector process 
```
