# DDD жаргон

[Общая шпаргалка по DDD](https://zharro.github.io/ddd/2016/12/13/ddd-cheat-sheet.html)

[Хорошая статья, откуда взял много выдержек](https://habr.com/ru/companies/otus/articles/353500/)

### Ограниченный контекст

В терминах DDD модель не монолитна, а разделена на несколько ограниченных контекстов

```Ограниченный контекст - это явная граница, внутри которой существует модель предметной области, которая отображает единый язык в модель программного обеспечения.```

Проще говоря, ограниченный контекст - это применение конкретной предметной области для решения конкретной задачи. То
есть для решения задачи Оплата в предметной
области Магазин мы представляем оплату конкретно наличкой или конкретно картой, а не просто абстрактную оплату

```Существование ограниченных контекстов объясняется организационными причинами. Чаще всего невозможно создать единую модель для всего предприятия, потому что такая модель не будет отражать реальную неоднородную структуру компании, разнящейся от отдела к отделу.```

```Скажем, решили вы заказать изготовление продукции. Пока вы ее не оплатите никто не поднимет пятой точки. Зато после оплаты заявка поступает в отдел производства. Отделу производства в свою очередь не важно оплачена заявка или нет. Для них актуальны сроки выполнения и наличие необходимых материалов на складе. Затем товар отправляется в доставку, которому вообще по барабану что это за товар. Их волнует только расстояние от склада отгрузки до точки доставки.```

### Агрегаты

```Агрегаты — это деревья объектов, обладающие инвариантом для группы, а не для единичного объекта. Доступ к агрегатам осуществляется через «Корень агрегации» — объект, находящийся в корне дерева. Таким образом, корень обеспечивает инвариант всей группы с помощью инкапсуляции.```

- Корневой объект имеет глобальную идентичность и в конечном счете отвечает за проверку инвариантов.
- Корневые объекты имеют глобальную идентичность. Внутренние сущности имеют локальную идентичность, уникальную только
  внутри агрегата.
- Ничто вне границы агрегата не может содержать ссылку на что-либо внутри, кроме корневого объекта. Корневой объект
  может связывать ссылки с внутренними объектами на другие объекты, но эти объекты могут использовать их только временно
  и не могут быть привязаны к ссылке.
- В качестве следствия вышеприведенного правила, только базовые корни могут быть получены непосредственно с запросами
  базы данных. Все остальные объекты должны быть найдены путем обхода ассоциаций.
- Объекты внутри агрегата могут содержать ссылки на другие совокупные корни.
- Операция удаления должна удалять сразу все в пределах общей границы (со сборкой мусора это легко. Поскольку внешних
  ссылок на что-либо, кроме корня, нет, удалите корень и все остальное будет собрано).
- Когда совершено изменение какого-либо объекта на границе агрегата, все инварианты агрегата должны быть удовлетворены.

Корень агрегата, который предоставляет метод фабрики для создания экземпляров агрегата другого типа (или внутренних
частей), будет нести основную ответственность за обеспечение его основного агрегирующего поведения, и метод фабрики
является лишь одним из них. Фабрики также могут обеспечить важный уровень абстракции, который защищает клиента от
зависимости от конкретного класса.

Бывают случаи, когда фабрика не нужна, и простого конструктора достаточно. Используйте конструктор, когда:

- Конструкция не сложная.
- Создание объекта не связано с созданием других, и все необходимые атрибуты передаются через конструктор.
- Разработчик заинтересован в реализации и, возможно, хочет выбрать стратегию для использования.
- Класс — тип. Нет иерархии, поэтому нет необходимости выбирать между списком конкретных реализаций.

### Сущность и Value Object

```Сущности и Value Object — это основные строительные блоки приложения, которые могут как входить в агрегаты, так и не входить. Их основное отличие в том, что у сущностей есть уникальный идентификатор, а у объектов-значений — нет.```

```Сущности — это объекты, доступные по идентификаторам в нашем приложении. Фактически, сущность представляет собой набор свойств, которые имеют уникальный идентификатор. Хорошим примером может служить ряд таблиц базы данных. Сущность изменчива, потому что она может изменять свои атрибуты (обычно с помощью сеттеров и геттеров), а также имеет жизненный цикл, то есть ее можно удалить.```

Value Object получает в себя raw значения, которые валидирует и преобразует. В Сущности уже передаются только Value
Object и мы можем быть уверены, что
значения точно правильные

### Anticorruption Layer

Anticorruption Layer - это фасад, который отвечает за первичную обработку данных, по сути валидацию. Он должен
применяться на всех точках входа в
отдельный слой DDD. То есть когда мы приняли на слое Application данные и передали их в репозиторий, а потом в домен, то
в домене все равно
должна производиться проверка



