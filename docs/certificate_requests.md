# Запросы на сертификаты

## Отправка запроса на сертификат

```
POST /api/ra/certRequests
```
- `userId` (string, uuid): Идентификатор пользователя
- `authorityName` (string, nullable): Имя экземпляра ЦС
- `rawRequest` (byte): Запрос на сертификат

**Ответ:**
- 201: Запрос успешно отправлен (uuid)
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Пользователь не найден

### Формат ответа (application/json)
```json
"string (uuid)"
```

---

## Получение списка запросов на сертификат

```
GET /api/ra/certRequests
```
- `folder` (string): Имя папки
- `userId` (string, uuid): Идентификатор пользователя
- `status` (string): new
- `minTime` (string, date-time): Нижняя граница времени
- `maxTime` (string, date-time): Верхняя граница времени
- `pageToken` (string): Токен страницы

**Ответ:**
- 200: Данные получены (массив CertRequestShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
```json
{
  "items": [
    {
      "id": "string (uuid)",
      "nameAttributes": { "OID": "значение", ... },
      "createdWhen": "string (date-time)",
      "resolvedWhen": "string (date-time)",
      "authRepliedWhen": "string (date-time)",
      "status": "New | Approved | Rejected | Completed | DeniedByCa"
    }
  ],
  "_links": {
    "self": { "href": "string" },
    "next": { "href": "string" }
  }
}
```

**Описание полей:**
- `items` — массив запросов (CertRequestShortOut):
    - `id` (uuid): Идентификатор запроса
    - `nameAttributes` (object): Атрибуты субъекта (OID: значение)
    - `createdWhen` (string, date-time): Время создания
    - `resolvedWhen` (string, date-time, nullable): Время рассмотрения
    - `authRepliedWhen` (string, date-time, nullable): Время ответа от ЦС
    - `status` (string): Статус (`New`, `Approved`, `Rejected`, `Completed`, `DeniedByCa`)
- `_links` — объект ссылок (HalLink):
    - `self` (object): Ссылка на текущий ресурс
    - `next` (object): Ссылка на следующую страницу
    - `href` (string): URI ресурса

---

## Получение данных запроса на сертификат

```
GET /api/ra/certRequests/{id}
```
- `id` (string, uuid): Идентификатор запроса

**Ответ:**
- 200: Данные запроса на сертификат успешно получены
- 404: Запрос на сертификат не найден

### Формат ответа (application/json)
```json
{
  "id": "string (uuid)",
  "nameAttributes": { "OID": "значение", ... },
  "createdWhen": "string (date-time)",
  "resolvedWhen": "string (date-time)",
  "authRepliedWhen": "string (date-time)",
  "status": "New | Approved | Rejected | Completed | DeniedByCa",
  "userId": "string (uuid)",
  "subject": "string",
  "creatorId": "string (uuid)",
  "creatorName": "string",
  "creatorLogin": "string",
  "resolverId": "string (uuid)",
  "resolverName": "string",
  "resolverLogin": "string",
  "version": "int",
  "publicKey": "string",
  "publicKeyParameters": "string",
  "publicKeyOid": "string",
  "publicKeyOidDescription": "string",
  "signature": "string",
  "signatureParameters": "string",
  "signatureOid": "string",
  "signatureOidDescription": "string",
  "templateOid": "string",
  "templateName": "string",
  "templateDisplayName": "string",
  "extensions": [
    {
      "oid": "string",
      "oidDescription": "string",
      "value": "string"
    }
  ],
  "rawRequest": "string (base64)",
  "errorMessage": "string",
  "folder": "string",
  "certificateId": "string (uuid)"
}
```

**Описание полей:**
- `id` (uuid): Идентификатор запроса
- `nameAttributes` (object): Атрибуты субъекта (OID: значение)
- `createdWhen` (string, date-time): Время создания
- `resolvedWhen` (string, date-time, nullable): Время рассмотрения
- `authRepliedWhen` (string, date-time, nullable): Время ответа от ЦС
- `status` (string): Статус (`New`, `Approved`, `Rejected`, `Completed`, `DeniedByCa`)
- `userId` (uuid): Идентификатор пользователя
- `subject` (string, nullable): Субъект
- `creatorId` (uuid): Идентификатор оператора, создавшего запрос
- `creatorName` (string, nullable): ФИО оператора
- `creatorLogin` (string, nullable): Логин оператора
- `resolverId` (uuid, nullable): Идентификатор оператора, рассмотревшего запрос
- `resolverName` (string, nullable): ФИО рассмотревшего
- `resolverLogin` (string, nullable): Логин рассмотревшего
- `version` (int): Версия запроса
- `publicKey` (string, nullable): Открытый ключ (hex)
- `publicKeyParameters` (string, nullable): Параметры ключа (hex)
- `publicKeyOid` (string, nullable): OID алгоритма ключа
- `publicKeyOidDescription` (string, nullable): Описание алгоритма
- `signature` (string, nullable): Подпись (hex)
- `signatureParameters` (string, nullable): Параметры подписи (hex)
- `signatureOid` (string, nullable): OID алгоритма подписи
- `signatureOidDescription` (string, nullable): Описание алгоритма подписи
- `templateOid` (string, nullable): OID шаблона
- `templateName` (string, nullable): Короткое имя шаблона
- `templateDisplayName` (string, nullable): Отображаемое имя шаблона
- `extensions` (array): Расширения (ExtensionInfo)
    - `oid` (string, nullable): OID расширения
    - `oidDescription` (string, nullable): Описание OID
    - `value` (string, nullable): Значение
- `rawRequest` (string, base64, nullable): Запрос в бинарном виде
- `errorMessage` (string, nullable): Сообщение об ошибке
- `folder` (string, nullable): Имя папки
- `certificateId` (uuid, nullable): Идентификатор выпущенного сертификата

---

## Поиск запроса на сертификат по атрибуту имени субъекта

```
GET /api/ra/certRequests/search
```
- `folder` (string): Имя папки
- `value` (string): Искомое значение (обязательно)
- `key` (string): OID атрибута
- `searchType` (string): Equals, StartsWith

**Ответ:**
- 200: Данные получены (массив CertRequestShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
(см. выше структуру CertRequestShortOut)

## Одобрение запроса на сертификат

```
POST /api/ra/certRequests/{id}/approve
```
- `id` (string, uuid): Идентификатор запроса
- `rawRequest` (byte): Одобренный запрос

**Ответ:**
- 200: Запрос успешно одобрен
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Запрос не найден

## Отклонение запроса на сертификат

```
POST /api/ra/certRequests/{id}/reject
```
- `id` (string, uuid): Идентификатор запроса

**Ответ:**
- 200: Запрос успешно отклонен
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Запрос не найден 