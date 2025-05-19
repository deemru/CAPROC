# Шаблоны сертификатов

## Получение списка шаблонов сертификата

```
GET /api/ra/templates
```

**Описание:**
Каждый шаблон содержит данные для формирования запроса на сертификат: OID, версии, параметры создания ключа и др.

**Ответ:**
- 200: Данные получены (массив объектов шаблонов)

### Формат ответа (application/json)
```json
[
  {
    "name": "string",
    "oid": "string",
    "majorVersion": 1,
    "minorVersion": 0,
    "displayName": "string",
    "description": "string",
    "certValidity": {
      "validityPeriod": {
        "periodType": "Years | Months | Weeks | Days | Hours | Minutes",
        "unitCount": 1
      },
      "specifyValidityPeriodInRequest": "Prohibit | Allow | Require",
      "specifyStartDateInRequest": "Prohibit | Allow | Require",
      "specifyExpirationDateInRequest": "Prohibit | Allow | Require"
    },
    "keyCreationSpec": {
      "machineKeySet": true,
      "keySpec": "Unspecified | KeyExchange | Signature",
      "exportablePrivateKey": true,
      "csps": [
        {
          "provType": 1,
          "provName": "string",
          "priority": 1
        }
      ]
    }
  }
]
```

**Описание полей:**
- `name` (string, nullable): Имя шаблона
- `oid` (string, nullable): Объектный идентификатор
- `majorVersion` (int): Старшая версия
- `minorVersion` (int): Младшая версия
- `displayName` (string, nullable): Отображаемое имя
- `description` (string, nullable): Описание
- `certValidity` (object): Настройки срока действия
    - `validityPeriod` (object): Интервал времени
        - `periodType` (string): Единица времени (`Years`, `Months`, `Weeks`, `Days`, `Hours`, `Minutes`)
        - `unitCount` (int): Длина интервала
    - `specifyValidityPeriodInRequest` (string): Разрешение на указание срока (`Prohibit`, `Allow`, `Require`)
    - `specifyStartDateInRequest` (string): Разрешение на указание даты начала (`Prohibit`, `Allow`, `Require`)
    - `specifyExpirationDateInRequest` (string): Разрешение на указание даты окончания (`Prohibit`, `Allow`, `Require`)
- `keyCreationSpec` (object): Спецификация создания ключа
    - `machineKeySet` (boolean): Ключ в хранилище компьютера
    - `keySpec` (string): Назначение ключа (`Unspecified`, `KeyExchange`, `Signature`)
    - `exportablePrivateKey` (boolean): Экспортируемый ключ
    - `csps` (array): Список поставщиков криптографии
        - `provType` (int): Тип поставщика
        - `provName` (string, nullable): Имя поставщика
        - `priority` (int): Приоритет 