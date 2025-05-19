# Папки

## Получение списка папок

```
GET /api/ra/folders
```

**Ответ:**
- 200: Данные получены (массив объектов папок)

### Формат ответа (application/json)
```json
[
  {
    "name": "string",
    "manageUser": true,
    "submitCertRequest": true,
    "resolveCertRequest": true,
    "submitRevRequest": true,
    "resolveRevRequest": true
  }
]
```

**Описание полей:**
- `name` (string, nullable): Имя папки
- `manageUser` (boolean): Разрешено ли управление пользователями
- `submitCertRequest` (boolean): Разрешена ли отправка запросов на сертификат
- `resolveCertRequest` (boolean): Разрешено ли рассмотрение запросов на сертификат
- `submitRevRequest` (boolean): Разрешена ли отправка запросов на отзыв
- `resolveRevRequest` (boolean): Разрешено ли рассмотрение запросов на отзыв 