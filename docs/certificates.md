# Сертификаты

## Получение списка сертификатов

```
GET /api/ra/certificates
```

**Параметры запроса:**
- `folder` (string): Имя папки
- `userId` (string, uuid): Идентификатор пользователя
- `certRequestId` (string, uuid): Идентификатор запроса
- `serialNumber` (string): Серийный номер сертификата (HEX)
- `minTime` (string, date-time): Нижняя граница времени создания
- `maxTime` (string, date-time): Верхняя граница времени создания
- `pageToken` (string): Токен страницы

**Ответ:**
- 200: Данные получены (массив объектов CertificateShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
```json
{
  "items": [
    {
      "id": "string (uuid)",
      "nameAttributes": { "OID": "значение", ... },
      "serialNumber": "string",
      "thumbprint": "string",
      "notBefore": "string (date-time)",
      "notAfter": "string (date-time)",
      "keyNotAfter": "string (date-time)",
      "createdWhen": "string (date-time)",
      "status": "NotYetValid | Valid | Revoked | KeyExpired | Expired"
    }
  ],
  "_links": {
    "self": { "href": "string" },
    "next": { "href": "string" }
  }
}
```

**Описание полей:**
- `items` — массив сертификатов (CertificateShortOut):
    - `id` (uuid): Идентификатор сертификата
    - `nameAttributes` (object): Атрибуты субъекта (OID: значение)
    - `serialNumber` (string, nullable): Серийный номер
    - `thumbprint` (string, nullable): Отпечаток
    - `notBefore` (string, date-time): Время начала действия
    - `notAfter` (string, date-time): Время окончания действия
    - `keyNotAfter` (string, date-time): Время окончания действия ключа
    - `createdWhen` (string, date-time): Время выпуска
    - `status` (string): Статус (`NotYetValid`, `Valid`, `Revoked`, `KeyExpired`, `Expired`)
- `_links` — объект ссылок (HalLink):
    - `self` (object): Ссылка на текущий ресурс
    - `next` (object): Ссылка на следующую страницу
    - `href` (string): URI ресурса

---

## Получение сертификата по идентификатору

```
GET /api/ra/certificates/{id}
```
- `id` (string, uuid): Идентификатор сертификата

**Ответ:**
- 200: Сертификат найден (объект с подробной информацией)
- 404: Сертификат не найден

### Формат ответа (application/json)
```json
{
  "id": "string (uuid)",
  "nameAttributes": { "OID": "значение", ... },
  "serialNumber": "string",
  "thumbprint": "string",
  "notBefore": "string (date-time)",
  "notAfter": "string (date-time)",
  "keyNotAfter": "string (date-time)",
  "createdWhen": "string (date-time)",
  "status": "NotYetValid | Valid | Revoked | KeyExpired | Expired",
  "certRequestId": "string (uuid)",
  "subject": "string",
  "issuer": "string",
  "userId": "string (uuid)",
  "version": "int",
  "publicKey": "string",
  "publicKeyParameters": "string",
  "publicKeyOid": "string",
  "publicKeyOidDescription": "string",
  "extensions": [
    {
      "oid": "string",
      "oidDescription": "string",
      "value": "string"
    }
  ],
  "signature": "string",
  "signatureParameters": "string",
  "signatureOid": "string",
  "signatureOidDescription": "string",
  "rawCertificate": "string (base64)",
  "revocationReason": "Unspecified | AffiliationChanged | CACompromise | KeyCompromise | CessationOfOperation | Superseded",
  "revokedWhen": "string (date-time)",
  "folder": "string"
}
```

**Описание полей:**
- `id` (uuid): Идентификатор сертификата
- `nameAttributes` (object): Атрибуты субъекта (OID: значение)
- `serialNumber` (string, nullable): Серийный номер
- `thumbprint` (string, nullable): Отпечаток
- `notBefore` (string, date-time): Время начала действия
- `notAfter` (string, date-time): Время окончания действия
- `keyNotAfter` (string, date-time): Время окончания действия ключа
- `createdWhen` (string, date-time): Время выпуска
- `status` (string): Статус (`NotYetValid`, `Valid`, `Revoked`, `KeyExpired`, `Expired`)
- `certRequestId` (uuid, nullable): Идентификатор запроса
- `subject` (string, nullable): Субъект
- `issuer` (string, nullable): Издатель
- `userId` (uuid): Идентификатор пользователя
- `version` (int): Версия сертификата
- `publicKey` (string, nullable): Открытый ключ (hex)
- `publicKeyParameters` (string, nullable): Параметры ключа (hex)
- `publicKeyOid` (string, nullable): OID алгоритма ключа
- `publicKeyOidDescription` (string, nullable): Описание алгоритма
- `extensions` (array): Расширения сертификата (ExtensionInfo)
    - `oid` (string, nullable): OID расширения
    - `oidDescription` (string, nullable): Описание OID
    - `value` (string, nullable): Значение
- `signature` (string, nullable): Подпись (hex)
- `signatureParameters` (string, nullable): Параметры подписи (hex)
- `signatureOid` (string, nullable): OID алгоритма подписи
- `signatureOidDescription` (string, nullable): Описание алгоритма подписи
- `rawCertificate` (string, base64, nullable): Сертификат
- `revocationReason` (string): Причина отзыва (`Unspecified`, `AffiliationChanged`, `CACompromise`, `KeyCompromise`, `CessationOfOperation`, `Superseded`)
- `revokedWhen` (string, date-time, nullable): Время отзыва
- `folder` (string, nullable): Имя папки

---

## Получение сертификата по серийному номеру

```
GET /api/ra/certificates/serialNumber/{serialNumber}
```
- `serialNumber` (string): Серийный номер сертификата

**Ответ:**
- 200: Сертификат найден (структура идентична предыдущему endpoint)
- 404: Сертификат не найден

---

## Поиск сертификата по атрибуту имени субъекта

```
GET /api/ra/certificates/search
```
- `folder` (string): Имя папки
- `value` (string): Искомое значение (обязательно)
- `key` (string): OID, serialNumber, thumbprint
- `searchType` (string): Equals, StartsWith

**Ответ:**
- 200: Данные получены (массив объектов CertificateShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
(см. выше структуру CertificateShortOut) 