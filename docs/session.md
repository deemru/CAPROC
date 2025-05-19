# Сессия

## Получение информации о текущей сессии

```
GET /api/ra/session
```

**Ответ:**
- 200: Данные получены (информация о клиенте и сертификатах)

### Формат ответа (application/json)
```json
{
  "clientName": "string",
  "tlsClientCertThumbprint": "string",
  "signingCertThumbprint": "string"
}
```

**Описание полей:**
- `clientName` (string, nullable): Имя клиента
- `tlsClientCertThumbprint` (string, nullable): Отпечаток TLS сертификата клиента
- `signingCertThumbprint` (string, nullable): Отпечаток сертификата подписи клиента 