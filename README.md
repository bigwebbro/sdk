# Tyin Merchant API SDK

SDK для интеграции с Tyin Merchant API. 
SDK предоставляет удобный интерфейс для работы с API: создание счетов, получение информации о счетах, проведение возвратов
и обработку webhook событий от API.

SDK обеспечивает:

* **Типизированные модели (DTO)**: сущности API представлены как строго типизированные PHP-классы.

* **Сериализация и десериализацию**: SDK преобразует модели данных в json для API, и обратно, позволяя работать с запросами и ответами в удобной форме PHP объектов.

* **Валидация данных на уровне моделей**: все модели, используемые для запросов в API проходят валидацию на соответствия требованиям 
(проверка формата суммы, обязательных полей и типов данных). Для полей со списочными значениями используются enum.

* **Подпись запросов и проверка подписи**: SDK проверяет подпись входящих webhook-событий, 
а также позволяет формировать корректно подписанные запросы к API.

* **Обработку ошибок**: SDK оборачивает ошибки валидации, API, транспортного уровня, позволяя работать с детерминированным списком исключений.

* **Повторы запросов**: в случае ответа API ошибкой в ряде случаев http клиент SDK автоматически повторит запросы. 

Использование SDK позволяет минимизировать ошибки при интеграции, ускорить разработку и обеспечить корректное взаимодействие с Tyin Merchant API.

# Требования 

* PHP 8.1+

Php-extensions:
* ext-json
* ext-iconv
* ext-filter

# Подключение

## Для проектов использующих composer

В корне composer.json указать:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/bigwebbro/sdk.git"
    }
],
```
И выполнить команду:
```
composer require bigwebbro/sdk
```

## Для проектов не использующих composer
Перейти на [станицу последнего релиза](https://github.com/bigwebbro/sdk/releases/latest), скачать Sdk.zip.
Распаковать архив и подключать в ваш проект через `require_once()` конфигурации автозагрузчика классов, расположенной в `vendor/autoload.php`:

```php
require_once(__DIR__ ."/../sdk/vendor/autoload.php");

use Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;

...

$client = (new ClientBuilder())
    ->setBaseUri('https://merchant-api.example/api/v2/')
    ...
    ->build()
;

$sdk = (new MerchantApiSdkBuilder())
    ->setClient($client)
    ...
    ->build()
;

...
```
После чего классы SDK можно будет использовать в вашем проекте.

# Структура
```
src
  ├─ Client                        // PSR-18 клиент с декораторами и исключениями
  ├─ Model                         // DTO сущностей API
  ├─ Serializer                    // Конфигурация и фабрика сериализаторов
  │   ├─ Denormalizer              // Кастомные денормалайзеры для моделей
  │   ├─ Normalizer                // Кастомные нормалайзеры для моделей
  │   └─ SerializerFactory.php     // Фабрика для создания symfony/serializer
  ├─ Service                       // Бизнес-логика и взаимодействие с API
  │   ├─ Handler                   // Обработчики запросов и ответов
  │   │   ├─ Exception             // Исключения, связанные с обработчиками
  │   │   │   ├─ Api               // Ошибки, связанные с API-ответами, содержат описание ошибки
  │   │   │   │   ├─ ApiMerchantErrorException.php
  │   │   │   │   ├─ EntityErrorException.php
  │   │   │   │   ├─ Error.php     // DTO ошибки
  │   │   │   │   ├─ ForbiddenException.php
  │   │   │   │   └─ UnauthorizedException.php
  │   │   │   ├─ Service           // Ответы API без дополнительного описания ошибки
  │   │   │   │   ├─ BlockedRequestException.php
  │   │   │   │   ├─ ServiceException.php
  │   │   │   │   ├─ ServiceUnavailableException.php
  │   │   │   │   └─ TimeoutException.php
  │   │   │   └─ Validation        // Ошибки валидации данных и де/сериализации
  │   │   │       ├─ DataTransformationException.php
  │   │   │       └─ ValidationException.php
  │   │   ├─ RequestHandler.php                // Обработчик исходящих HTTP-запросов
  │   │   ├─ RequestHandlerInterface.php       // Интерфейс обработчика запросов
  │   │   ├─ ResponseHandler.php               // Обработчик входящих HTTP-ответов
  │   │   └─ ResponseHandlerInterface.php      // Интерфейс обработчика ответов
  │   ├─ AbstractRequestService.php            // Базовый сервис для работы с запросами
  │   ├─ CallbackService.php                   // Сервис обработки callback-запросов
  │   ├─ CallbackServiceInterface.php          // Интерфейс callback-сервиса
  │   ├─ InvoicesService.php                   // Сервис для управления счетами (invoice)
  │   └─ InvoicesServiceInterface.php          // Интерфейс сервиса счетов
  ├─ Sign                        // Генерация и проверка подписей запросов
  ├─ Validator                   // Кастомные валидаторы для symfony/validator
  ├─ functions.php               // Глобальные вспомогательные функции SDK
  ├─ MerchantApiSdk.php          // Основной класс SDK, точка входа
  ├─ MerchantApiSdkBuilder.php   // Реализация билдера SDK
  ├─ MerchantApiSdkBuilderInterface.php // Интерфейс билдера SDK
  └─ MerchantApiSdkInterface.php        // Интерфейс основного SDK
```

# Инициализация

```php
use Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator;
use Tiyn\MerchantApiSdk\Client\Decorator\Clock\Clock;
use Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder;
use Tiyn\MerchantApiSdk\MerchantApiSdkBuilder;

$psrClient = (new ClientBuilder())
    ->setBaseUri('https://merchant-api.example/api/v2/')
    ->setTimeout($timeout)
    ->enableRetry($maxAttempts)
    ->addDecorator(new ClientLoggingDecorator($psrLogger, new Clock()))
    ->build()
;

$sdk = (new MerchantApiSdkBuilder())
    ->setClient($psrClient)
    ->setApiKey($this->apiKey)
    ->setSecretPhrase($this->secretPhrase)
    ->build()
;
```
Для использования SDK нужно сконфигурировать клиент с помощью `ClientBuilder` и `MerchantApiSdkBuilder`.

### Tiyn\MerchantApiSdk\Client\Guzzle\ClientBuilder reference

Создает и декорирует PSR-18 http клиент на базе guzzle.

* `setBaseUri(string $baseUrl): self` принимает uri, относительного которого будет выстраивать url запросов. Uri **должно** заканчиваться на `/`.
Логику работы с uri см. [здесь](https://docs.guzzlephp.org/en/stable/quickstart.html#creating-a-client) в разделе base_uri.

* `setTimeout(int $timeout): self` таймаут соединения в секундах.

* `enableRetry(int $maxAttempts, float $multiplier): self` повтор запроса в случае 408, 429, 500, 502, 503 http кодов ответа. 
\$maxAttempts - количество повторных попыток (не больше 5), \$multiplier - умножитель времени между попытками.

* `setOptions(array $options): self` массив опций для конфигурирования guzzle.

* `addDecorator(ClientInterface $decorator): self` добавляет декораторы клиента.

### Tiyn\MerchantApiSdk\MerchantApiSdkBuilder reference

Создает объект SDK.  

* `setClient(ClientInterface $client): self`
Позволяет передать готовый PSR-18 HTTP клиент для использования в SDK.

* `setSecretPhrase(string $secretPhrase): self`
Задает секретную фразу, используемую для генерации подписи (X-Sign) при отправке запросов и получении webhooks.

* `setApiKey(string $apiKey): self`
Устанавливает API-ключ, который будет добавляться в заголовок (X-Api-Key) каждого запроса.

* `setSerializer(SerializerInterface $serializer): self` 
Передает сериализатор (Symfony\Component\Serializer\SerializerInterface), который отвечает за преобразование моделей SDK в JSON и обратно.
Метод необходим для конфигурации SDK в рамках symfony framework, если SDK используется в другой среде, то конфигурировать сериализатор **нет необходимости**, в момент
вызова `build()` MerchantApiSdkBuilder сконфигурирует сериализатор самостоятельно.

* `setValidator(ValidatorInterface $validator): self`
Передает валидатор (например, Symfony\Component\Validator\Validator\ValidatorInterface), применяемый для проверки корректности данных перед отправкой запроса.
Метод необходим для конфигурации SDK в рамках symfony framework, если SDK используется в другой среде, то конфигурировать валидатор **нет необходимости**, в момент
вызова `build()` MerchantApiSdkBuilder создаст валидатор самостоятельно.

### Декорирование клиента

Клиент может использовать декораторы, расширяющие его функционал. 
Декораторы обязаны реализовать соответствующие интерфейсы (см. реализацию `Tiyn\MerchantApiSdk\Client\Decorator\ClientLoggingDecorator`). 
Декораторы можно добавить к клиенту с помощью метода `ClientBuilder::addDecorator`.

### Логирование

"Из коробки" доступен логирующий декоратор, который логирует факт запроса и ответа от Merchant API.
В конструктор логирующего декоратора необходимо передавать логер реализующий PSR-3 и `Tiyn\MerchantApiSdk\Client\Decorator\Clock\Clock`:
```php
...
    ->addDecorator(new ClientLoggingDecorator($psrLogger, new Clock()))
...
```

Пример логов декоратора:
```
[2025-11-05T22:01:10.760928+03:00] app.INFO: Request to Merchant API {"method":"POST","endpoint":"invoices"} []
[2025-11-05T22:01:16.435213+03:00] app.INFO: Response from Merchant API {"http_code":200,"time":"5674 ms"} []
```

# Использование
`Tiyn\MerchantApiSdk\MerchantApiSdk` прокси класс, передающий вызовы конкретным сервисам для работы с Merchant API. 

## Tiyn\MerchantApiSdk\Service\InvoicesService

### Создание счета
* `Tiyn\MerchantApiSdk\Service\InvoicesService::createInvoice` реализует создание счета из [документации](https://business.tiyn.io/documentation/#c24c154824).
На вход требуется передать DTO `Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest` заполненную данными по создаваему счету.  
Данные в DTO будут провалидированы на соответствие требованиям метода API. 

* [Пример в demo](https://github.com/bigwebbro/sdk-demo/blob/main/src/Controller/InvoiceController.php#L26) 

Пример:
```php
use Tiyn\MerchantApiSdk\Client\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceRequest;
use Tiyn\MerchantApiSdk\Model\Invoice\Enum\DeliveryMethodEnum;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\ValidationException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;

...

$createInvoiceRequest = (new CreateInvoiceRequest())
    ->setExternalId($orderId)
    ->setAmount('1005.5')
    ->setDescription('Счет на оплату заказа №ХХХ')
    ->setDeliveryMethod(DeliveryMethodEnum::default())
    ->setCustomerEmail('em...@...l.com')
    ->setCustomerPhone('+7999...')
    ->setCustomData([...])
    ->setExpirationDate((new \DateTimeImmutable('now'))->add(new \DateInterval('P1D')))

try {
    /**
     * @var \Tiyn\MerchantApiSdk\Model\Invoice\CreateInvoiceResponse $createInvoiceResponse 
     */
    $createInvoiceResponse = $merchantApiSdk->invoice()->createInvoice($invoiceRequest);
} catch (ValidationException $exception) {
    // Ошибки валидации данных - пустые обязательные поля, некорректная сумма и т.п 
    $validationProblem = $exception->getMessage();
    ...
} catch (ApiMerchantErrorException $exception) {
    // Ошибки API c моделью Error
    // Содержат код ошибки из документации и другие поля 
    // Выбрасываются при 4хх, если сервис отдал json описание ошибки
    ...

} catch (ServiceException $exception) {
    // Обработка 5xx и 418 статуса ответа
    ...
} catch (ConnectionException $exception) {
    // Транспортные ошибки - таймаут соединения, недоступность API и т.п.
    ...
}

$uuid = $createInvoiceResponse->getUuid();
$paymentLink = $createInvoiceResponse->getPaymentLink();
...
```

### Получение данных счета

* `Tiyn\MerchantApiSdk\Service\InvoicesService::getInvoice` реализует получение данных счета по его уникальному идентификатору ([документация](https://business.tiyn.io/documentation/#a1a755e371)).
На вход требует DTO в которую кладут uuid счета.

* [Пример в demo](https://github.com/bigwebbro/sdk-demo/blob/main/src/Controller/InvoiceController.php#L69)

Пример:
```php
use Tiyn\MerchantApiSdk\Client\Exception\Transport\ConnectionException;
use Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceRequest;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;

...

$getInvoiceRequest = new GetInvoiceRequest($uuid);

try {
    /**
     * @var \Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse $getInvoiceResponse
     */
    $getInvoiceResponse = $merchantApiSdk->invoice()->getInvoice($getInvoiceRequest);
} catch (ApiMerchantErrorException $exception) {
    ...
} catch (ServiceException $exception) {
    ...
} catch (ConnectionException $exception) {
    ...
}

...
```

### Возврат

* `Tiyn\MerchantApiSdk\Service\InvoicesService::makeRefundByInvoice` реализует запрос на возврат денежных средств ([документация](https://business.tiyn.io/documentation/#8708c325a8)).  
На вход принимает уникальный индентификатор счета (полученный при его создании) и DTO с данными для возврата.  

* [Пример в demo](https://github.com/bigwebbro/sdk-demo/blob/main/src/Controller/RefundController.php#L14)

Пример:
```php
use Tiyn\MerchantApiSdk\Model\Invoice\CreateRefundRequest;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;

...

$createRefundRequest = (new CreateRefundRequest())
    ->setRequestId($requestId)
    ->setAmount('1005.5')
    ->setReason('...')
;

try {
    /**
     * @var \Tiyn\MerchantApiSdk\Model\Refund\CreateRefundResponse $createRefundResponse
     */
    $createRefundResponse = $merchantApiSdk->invoice()->makeRefundByInvoice($uuid, $createRefundRequest);
} catch (ValidationException $exception) {
    ...
} catch (ApiMerchantErrorException $exception) {
    ...
} catch (ServiceException $exception) {
    ...
} catch (ConnectionException $exception) {
    ...
}

...
```

## Tiyn\MerchantApiSdk\Service\CallbackService

Сервис для работы с callback запросами от Merchant API. В общем случае проверяет подпись запроса и возвращает DTO для дальнейшей работы с ней. 

### Callback изменение статуса счета

* `Tiyn\MerchantApiSdk\Service\CallbackService::handleInvoiceCallback` обрабатывает callback-запросы от Merchant API о событиях связанных со счетом (оплата, отмена и т.п.). [Раздел в документации](https://business.tiyn.io/documentation/#11f8e1d37f). 
В метод передается заголовок `X-Sign`, пришедший вместе с callback запросом и тело запроса (json строка) as is.
Возвращает DTO счета для дальнейшей обработки. 

* [Пример в demo](https://github.com/bigwebbro/sdk-demo/blob/main/src/Controller/CallbackController.php#L25)

Пример:
```php
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Validation\DataTransformationException;
use Tiyn\MerchantApiSdk\Sign\SignException;

...

try {
    /**
     * @var \Tiyn\MerchantApiSdk\Model\Invoice\GetInvoiceResponse $invoice
     * 
     * обработка запроса от сервиса об изменении счета
     * метод требует предать на вход заголовок подписи X-Sign и тело запроса as is
     */
    $invoice = $merchantApiSdk->callback()->handleInvoiceCallback($request->headers->get('x-sign', ''), $content);
} catch (SignException $exception) {
    // Ошибка проверки подписи запроса
} catch (DataTransformationException $exception) {
    // Ошибка десериализации тела запроса
}
```

## Обработка ошибок

В примерах выше показан ряд исключений, которые оборачивают ошибки API, ошибки низкого уровня (сеть и т.п.) и используемых в SDK библиотек. 
Хотелось бы заострить внимание на типе исключений и его наследниках, возникающих в случае если API отдало описание ошибки в ответе - `Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException`.
Такое исключение содержит DTO `\Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\Error`, которое, 
в свою очередь, содержит сообщение (для удобства это сообщение передается в `ApiMerchantErrorException` и `$exception->getMessage()` вернет его), [код ошибки из документации](https://business.tiyn.io/documentation/#tiyn-business-10) 
и correlationId запроса, который можно использовать при обращении к тех. поддержке Merchant API.  

Исключения типа `Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException` возникают в случае если API не отдало описание ошибки и 5хх кодов.

Пример:

```php
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\ApiMerchantErrorException;
use Tiyn\MerchantApiSdk\Service\Handler\Exception\Service\ServiceException;

try {
    ...
} catch (ApiMerchantErrorException $exception) {
    /**
     * @var \Tiyn\MerchantApiSdk\Service\Handler\Exception\Api\Error 
     */
    $error = $exception->getError();
    $errorCode = $error->getCode() // код ошибки из документации 
    $message = $exception->getMessage(); // сообщение ошибки, аналогично $error->getMessage() 
    $httpStatus = $exception->getCode(); // http статус ответа
    ...
} catch (ServiceException $exception) {
    $httpStatus = $exception->getCode(); // http статус ответа
    ...   
} catch(...)
     

```