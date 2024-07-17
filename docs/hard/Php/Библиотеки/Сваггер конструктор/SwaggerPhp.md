Библиотека, которая позволяет описывать конструкции в сваггере с помощью аттрибутов на PHP 8.3. Удобство такого способа в том, что работает автодополнение по возможным параметрам. Но проблема в том, что не все из этого сделано удобно, а документация на сорсе описана просто примерами

[Ссылка на сорцы](https://github.com/zircote/swagger-php)

#### Команда для запуска

``` shell
php ./vendor/bin/openapi ./qrInformer/entities/_swagger_docs -o ./swagger.json 
```

#### Как я обычно описываю сваггер

Я создаю интерфейс, в котором полностью описываю конструкцию для генерации сваггера на каждую отдельную функцию контроллера
Если мне нужно описать модель, то создаю интерфейс для модели и описываю функцию dto

Пример простой конструкции:
```php

// Вот эта штука описывает подключение библиотеки
use OpenApi\Attributes as OA; 
// Тег обычно выносится отдельно, чтобы у всех функций одного контроллера он был один
const TAG = 'Tech info';

interface ServerControllerInterface
{
    #[OA\Get(
        path: '/server/version',
        operationId: 'version',
        security: [],
        tags: [TAG],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Get current code version',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'version',
                            type: 'string',
                            example: '1.0'
                        ),
                        new OA\Property(
                            property: 'build_sha',
                            type: 'string',
                            example: 'f4bca4c4ca3398cee9ed2f6ab09edf74b9d75f2c'
                        ),
                    ]
                )
            ),
        ]
    )
    ]
    public static function getVersion(): void;
}
```

#### Полезные конструкции

###### Хедеры первоначальной настройки

```php
//Описание версии и способов авторизации
#[OA\OpenApi(openapi: '3.1.0', security: [['session_key' => []]])]
//Название
#[OA\Info(version: "0.1", title: "Qr informer")]
//Для вставки хедера для авторизации
#[OA\SecurityScheme(
    securityScheme: "session_key",
    type: "http",
    description: 'auth header as for staff',
    scheme: 'bearer',
)]
//Еще один вариант, который справедлив только при первой авторизации приложения
#[OA\SecurityScheme(
    securityScheme: 'onetimeCode',
    type: "apiKey",
    description: 'auth header as for staff',
    name: 'Authorization',
    in: 'header'
)]
//Дополнительные параметры в хедере, которые будут использоваться во всех запросах
#[OA\Parameter(
	name: 'Custom-Version', 
	in: 'header', 
	required: true, 
	ref: new OA\Schema(
    	type: 'string', 
    	example: '1.6'
	)
)]
//Ссылка на сервер, куда будем отправлять запросы
#[OA\Server(url: "https://google.com")]
interface MainRouterInterface
```

###### Общее описание ошибки

```php
interface ErrorResponseInterface{
    #[OA\Schema(
        schema: 'error',
        properties: [
            new OA\Property(
                property: 'status',
                type: 'bool',
                example: false
            ),
            new OA\Property(
                property: 'message',
                type: 'string',
                example: 'Error message'
            ),
            new OA\Property(
                property: 'error_token',
                type: 'string',
                example: 'ERROR'
            ),
        ])
    ]
    public function __construct(string $message, bool $status = false,);
   }
```

###### Для загрузки бинарного файла

```php
interface ImageControllerInterface
{
    #[OA\Post(
        path: '/api/protected/manager/image',
        operationId: 'uploadImageForPublicUsage',
        // Вот здесь самая интересная часть
        requestBody: new OA\RequestBody(
            required: true, 
            content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                required: ['image'],
                properties: [
                    new OA\Property(
                        property: 'image',
                        type: 'string',
                        format: 'binary'
                    ),
                ]
            )
        )
        ),
        tags: [TAG],
        responses: [
            new OA\Response(
                response: '200',
                description: 'saved file links',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'link',
                            type: 'string'
                        ),
                    ]
                )
            ),
        ]
    )
    ]
    public static function uploadImageForPublicUsage();
}
```

###### Пример круда с моделью
```php

use OpenApi\Attributes as OA;

const TAG = 'Promotion';
interface PromotionControllerInterface
{

    #[OA\Get(
        path: '/api/protected/manager/promotion',
        operationId: 'getManyPromotions',
        tags: [TAG],
        parameters: [
            new OA\Parameter(
                name: 'since_id',
                description: 'Start from id',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    default: '1',
                ),
            ),
            new OA\Parameter(
                name: 'n',
                description: 'Count to display',
                in: 'query',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    default: '30',
                ),
            ),
            // Просто для того чтобы запомнить как добавлять данные в хедер
             new OA\Parameter(
                name: 'X-Eds',
                description: 'HMAC string',
                in: 'header',
                required: true,
                example: '08f025e18c488d69d6168a7c79cd3a10f56525d39bf78f58af681862739143ab'
             ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: "promotions",
                content: new OA\JsonContent(
                    properties: [
                        new Oa\Property(
                            property: 'promotions',
                            type: 'array',
                            items: new OA\Items(
                                ref: '#/components/schemas/promotion',
                            )
                        ),
                        new OA\Property(
                            property: 'last',
                            type: 'int',
                            example: '500'
                        ),
                        new OA\Property(
                            property: 'count',
                            type: 'int',
                            example: '15'
                        ),
                        new OA\Property(
                            property: 'is_last',
                            type: 'boolean',
                            example: 'true'
                        ),
                    ],
                    type: 'object'
                )
            ),


        ]
    )
    ]
    public static function getPromotions(): void;

    #[OA\Post(
        path: '/api/protected/manager/promotion',
        operationId: 'createPromotion',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\JsonContent(
                    required: ['name', 'active_from', 'active_to', 'image'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'description',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'active_from',
                            type: 'datetime',
                        ),
                        new OA\Property(
                            property: 'active_to',
                            type: 'datetime',
                        ),
                        new OA\Property(
                            property: 'image',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'condition',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'link',
                            type: 'string',
                        ),
                    ],
                    type: 'object'
                ),
            ]
        ),
        tags: [TAG],
        responses: [
            new OA\Response(
                response: '200',
                description: 'new promotion model',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/promotion',
                    type: 'object'
                )
            ),
        ]
    )
    ]
    public static function createPromotion(): void;

    #[OA\Get(
        path: '/api/protected/manager/promotion/{id}',
        operationId: 'getPromotion',
        tags: [TAG],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'promotion id',
                in: 'path',
                required: true,
                example: 10,
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'promotion model by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/promotion',
                    type: 'object'
                )
            ),
        ]
    )
    ]
    public static function getPromotion($id): void;

    #[OA\Put(
        path: '/api/protected/manager/promotion/{id}',
        operationId: 'updatePromotion',
        requestBody: new OA\RequestBody(
            content: [
                new OA\JsonContent(
                    required: ['name', 'active_from', 'active_to', 'image'],
                    properties: [
                        new OA\Property(
                            property: 'name',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'description',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'active_from',
                            type: 'datetime',
                        ),
                        new OA\Property(
                            property: 'active_to',
                            type: 'datetime',
                        ),
                        new OA\Property(
                            property: 'image',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'condition',
                            ref: '#/components/schemas/stringMultilang',
                            type: 'object',
                        ),
                        new OA\Property(
                            property: 'link',
                            type: 'string',
                        ),
                    ],
                    type: 'object'
                ),
            ]
        ),
        tags: [TAG],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'promotion id',
                in: 'path',
                required: true,
                example: 10,
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'updated promotion model',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/promotion',
                    type: 'object'
                )
            ),


        ]
    )
    ]
    public static function updatePromotion($id): void;

    #[OA\Delete(
        path: '/api/protected/manager/promotion/{id}',
        operationId: 'deletePromotion',
        tags: [TAG],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'promotion id',
                in: 'path',
                required: true,
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'delete status',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'bool',
                            default: true
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )
    ]
    public static function deletePromotion($id): void;
}

interface PromotionModelInterface
{

    #[OA\Schema(
        schema: 'promotion',
        properties: [
            new OA\Property(
                property: 'id',
                type: 'int',
                example: '1'
            ),
            new OA\Property(
                property: 'name',
                ref: '#/components/schemas/stringMultilang',
                type: 'object',
            ),
            new OA\Property(
                property: 'description',
                ref: '#/components/schemas/stringMultilang',
                type: 'object',
            ),
            new OA\Property(
                property: 'active_from',
                type: 'datetime',
            ),
            new OA\Property(
                property: 'active_to',
                type: 'datetime',
            ),
            new OA\Property(
                property: 'image',
                ref: '#/components/schemas/stringMultilang',
                type: 'object',
            ),
            new OA\Property(
                property: 'condition',
                ref: '#/components/schemas/stringMultilang',
                type: 'object',
            ),
            new OA\Property(
                property: 'link',
                type: 'string',
            ),
            new OA\Property(
                property: 'created_at',
                type: 'datetime',
            ),
        ])
    ]
    public function dto();
}
```