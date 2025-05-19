# Запросы на отзыв

## Отправка запроса на отзыв сертификата

```
POST /api/ra/revRequests
```
- `rawRequest` (byte): Запрос на отзыв

**Ответ:**
- 201: Запрос успешно отправлен (uuid)
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Сертификат не найден

### Формат ответа (application/json)
```json
"string (uuid)"
```

---

## Получение списка запросов на отзыв

```
GET /api/ra/revRequests
```
- `folder` (string): Имя папки
- `certId` (string, uuid): Идентификатор сертификата
- `userId` (string, uuid): Идентификатор пользователя
- `status` (string): new
- `minTime` (string, date-time): Нижняя граница времени
- `maxTime` (string, date-time): Верхняя граница времени
- `pageToken` (string): Токен страницы

**Ответ:**
- 200: Данные получены (массив RevRequestShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
```json
{
  "items": [
    {
      "revRequestId": "string (uuid)",
      "createdWhen": "string (date-time)",
      "resolvedWhen": "string (date-time)",
      "authRepliedWhen": "string (date-time)",
      "serialNumber": "string",
      "thumbprint": "string",
      "status": "New | DeniedByCa | Completed | Approved | Rejected"
    }
  ],
  "_links": {
    "self": { "href": "string" },
    "next": { "href": "string" }
  }
}
```

**Описание полей:**
- `items` — массив запросов на отзыв (RevRequestShortOut):
    - `revRequestId` (uuid): Идентификатор запроса
    - `createdWhen` (string, date-time): Время создания
    - `resolvedWhen` (string, date-time, nullable): Время рассмотрения
    - `authRepliedWhen` (string, date-time, nullable): Время ответа от ЦС
    - `serialNumber` (string, nullable): Серийный номер сертификата
    - `thumbprint` (string, nullable): Отпечаток сертификата
    - `status` (string): Статус (`New`, `DeniedByCa`, `Completed`, `Approved`, `Rejected`)
- `_links` — объект ссылок (HalLink):
    - `self` (object): Ссылка на текущий ресурс
    - `next` (object): Ссылка на следующую страницу
    - `href` (string): URI ресурса

---

## Получение данных запроса на отзыв сертификата

```
GET /api/ra/revRequests/{id}
```
- `id` (string, uuid): Идентификатор запроса

**Ответ:**
- 200: Данные запроса на отзыв успешно получены (RevRequestOut)
- 404: Запрос не найден

### Формат ответа (application/json)
```json
{
  "revRequestId": "string (uuid)",
  "createdWhen": "string (date-time)",
  "resolvedWhen": "string (date-time)",
  "authRepliedWhen": "string (date-time)",
  "serialNumber": "string",
  "thumbprint": "string",
  "status": "New | DeniedByCa | Completed | Approved | Rejected",
  "certificateId": "string (uuid)",
  "userId": "string (uuid)",
  "creatorId": "string (uuid)",
  "creatorName": "string",
  "creatorLogin": "string",
  "resolverId": "string (uuid)",
  "resolverName": "string",
  "resolverLogin": "string",
  "revocationReason": "Unspecified | AffiliationChanged | CACompromise | KeyCompromise | CessationOfOperation | Superseded",
  "rawRequest": "string (base64)",
  "errorMessage": "string",
  "folder": "string"
}
```

**Описание полей:**
- `revRequestId` (uuid): Идентификатор запроса
- `createdWhen` (string, date-time): Время создания
- `resolvedWhen` (string, date-time, nullable): Время рассмотрения
- `authRepliedWhen` (string, date-time, nullable): Время ответа от ЦС
- `serialNumber` (string, nullable): Серийный номер сертификата
- `thumbprint` (string, nullable): Отпечаток сертификата
- `status` (string): Статус (`New`, `DeniedByCa`, `Completed`, `Approved`, `Rejected`)
- `certificateId` (uuid): Идентификатор сертификата
- `userId` (uuid): Идентификатор пользователя
- `creatorId` (uuid): Идентификатор оператора, создавшего запрос
- `creatorName` (string, nullable): ФИО оператора
- `creatorLogin` (string, nullable): Логин оператора
- `resolverId` (uuid, nullable): Идентификатор оператора, рассмотревшего запрос
- `resolverName` (string, nullable): ФИО рассмотревшего
- `resolverLogin` (string, nullable): Логин рассмотревшего
- `revocationReason` (string): Причина отзыва (`Unspecified`, `AffiliationChanged`, `CACompromise`, `KeyCompromise`, `CessationOfOperation`, `Superseded`)
- `rawRequest` (string, base64, nullable): Запрос в бинарном виде
- `errorMessage` (string, nullable): Сообщение об ошибке
- `folder` (string, nullable): Имя папки

---

## Одобрение/отклонение запроса на отзыв

```
POST /api/ra/revRequests/{id}/approve
POST /api/ra/revRequests/{id}/reject
```
- `id` (string, uuid): Идентификатор запроса
- `rawRequest` (byte): Одобренный запрос (approve)

**Ответ:**
- 200: Запрос успешно одобрен/отклонен
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Запрос не найден

## Одобрение запроса на отзыв

```
POST /api/ra/revRequests/{id}/approve
```
- `id` (string, uuid): Идентификатор запроса
- `rawRequest` (byte): Одобренный запрос

**Ответ:**
- 200: Запрос успешно одобрен
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Запрос не найден

## Отклонение запроса на отзыв

```
POST /api/ra/revRequests/{id}/reject
```
- `id` (string, uuid): Идентификатор запроса

**Ответ:**
- 200: Запрос успешно отклонен
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Запрос не найден 