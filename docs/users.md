# Пользователи

## Получение политики имен пользователей

```
GET /api/ra/namePolicy
```

**Ответ:**
- 200: Данные получены (массив разрешённых атрибутов имени пользователя)

### Формат ответа (application/json)
```json
{
  "nameAttributes": [
    {
      "oid": "string",
      "stringIdentifier": "string",
      "displayName": "string",
      "required": true,
      "minLength": 1,
      "maxLength": 255,
      "allowedValues": ["string"],
      "regex": "string",
      "retired": false
    }
  ]
}
```

**Описание полей:**
- `nameAttributes` (array): Список разрешённых атрибутов имени пользователя (NameAttributeInfo)
    - `oid` (string, nullable): OID атрибута
    - `stringIdentifier` (string, nullable): Строковый идентификатор
    - `displayName` (string, nullable): Отображаемое имя
    - `required` (boolean): Является ли обязательным
    - `minLength` (int, nullable): Минимальная длина
    - `maxLength` (int, nullable): Максимальная длина
    - `allowedValues` (array, nullable): Допустимые значения
    - `regex` (string, nullable): Регулярное выражение
    - `retired` (boolean): Признак выведенного из эксплуатации

---

## Создание пользователя

```
POST /api/ra/users
```
- `nameAttributes` (object): Атрибуты имени пользователя (OID: значение)
- `folder` (string): Имя папки

**Ответ:**
- 201: Пользователь создан (uuid)
- 400: Неверный запрос
- 403: Действие запрещено

### Формат ответа (application/json)
```json
"string (uuid)"
```

---

## Получение списка пользователей

```
GET /api/ra/users
```
- `folder` (string): Имя папки
- `minTime` (string, date-time): Нижняя граница времени
- `maxTime` (string, date-time): Верхняя граница времени
- `pageToken` (string): Токен страницы

**Ответ:**
- 200: Данные получены (массив UserShortOut)

### Формат ответа (application/json)
```json
{
  "items": [
    {
      "id": "string (uuid)",
      "nameAttributes": { "OID": "значение", ... },
      "createdWhen": "string (date-time)"
    }
  ],
  "_links": {
    "self": { "href": "string" },
    "next": { "href": "string" }
  }
}
```

**Описание полей:**
- `items` — массив пользователей (UserShortOut):
    - `id` (uuid): Идентификатор пользователя
    - `nameAttributes` (object): Атрибуты имени пользователя (OID: значение)
    - `createdWhen` (string, date-time): Время создания
- `_links` — объект ссылок (HalLink):
    - `self` (object): Ссылка на текущий ресурс
    - `next` (object): Ссылка на следующую страницу
    - `href` (string): URI ресурса

---

## Изменение данных пользователя

```
POST /api/ra/users/{id}
```
- `id` (string, uuid): Идентификатор пользователя
- `nameAttributes` (object): Новые атрибуты имени пользователя

**Ответ:**
- 200: Данные пользователя изменены
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Пользователь не найден

---

## Получение данных пользователя

```
GET /api/ra/users/{id}
```
- `id` (string, uuid): Идентификатор пользователя

**Ответ:**
- 200: Данные получены (объект пользователя)
- 404: Пользователь не найден

### Формат ответа (application/json)
```json
{
  "id": "string (uuid)",
  "nameAttributes": { "OID": "значение", ... },
  "createdWhen": "string (date-time)",
  "creatorId": "string (uuid)",
  "creatorName": "string",
  "creatorLogin": "string",
  "distinguishedName": "string",
  "folder": "string"
}
```

**Описание полей:**
- `id` (uuid): Идентификатор пользователя
- `nameAttributes` (object): Атрибуты имени пользователя (OID: значение)
- `createdWhen` (string, date-time): Время создания
- `creatorId` (uuid): Идентификатор оператора, создавшего пользователя
- `creatorName` (string, nullable): ФИО оператора
- `creatorLogin` (string, nullable): Логин оператора
- `distinguishedName` (string, nullable): Различающееся имя пользователя
- `folder` (string, nullable): Имя папки

---

## Поиск пользователя по атрибуту имени

```
GET /api/ra/users/search
```
- `folder` (string): Имя папки
- `value` (string): Искомое значение (обязательно)
- `key` (string): OID атрибута
- `searchType` (string): Equals, StartsWith

**Ответ:**
- 200: Данные получены (массив UserShortOut)
- 400: Неверный запрос

### Формат ответа (application/json)
(см. выше структуру UserShortOut)

---

## Перемещение пользователя в другую папку

```
POST /api/ra/users/{id}/move
```
- `id` (string, uuid): Идентификатор пользователя
- `folder` (string): Имя папки

**Ответ:**
- 200: Данные пользователя изменены
- 400: Неверный запрос
- 403: Действие запрещено
- 404: Пользователь не найден 