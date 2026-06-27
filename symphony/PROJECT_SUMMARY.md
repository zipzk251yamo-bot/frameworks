# 🎵 Song API CRUD - Проектна структура та опис

## 📋 Загальна інформація

**Проект:** RESTful API для управління піснями  
**Технологія:** Symfony 6.4 + PHP 8.2  
**Сховище даних:** JSON файл (`var/songs.json`)  
**Розробник:** BOGDANIST  
**Email:** bogddanist@gmail.com  
**Дата створення:** 2026-06-16

---

## 📁 Структура файлів

```
my_project_directory/
├── src/
│   ├── Controller/
│   │   └── SongController.php          ✅ Основний контролер CRUD
│   ├── Entity/
│   │   └── Song.php                    ✅ Entity клас для пісні
│   ├── Service/
│   │   └── SongManager.php             ✅ Сервіс для роботи з JSON
│   └── Kernel.php
├── var/
│   ├── songs.json                      ✅ JSON сховище з даними
│   ├── cache/
│   ├── log/
│   └── data/
├── public/
├── templates/
├── SONG_API_README.md                  📖 Повна документація API
├── TEST_API.md                         🧪 Приклади тестування
├── ANNOTATIONS_GUIDE.md                📝 Опис анотацій
├── PROJECT_SUMMARY.md                  📋 Цей файл
├── songs-api.postman_collection.json   📮 Postman колекція
├── composer.json
├── phpunit.dist.xml
└── ... інші файли Symfony проекту
```

---

## 🎯 Реалізовані можливості

### ✅ CRUD Операції

| Операція | HTTP | URL | Статус |
|----------|------|-----|--------|
| Читання всіх пісень | GET | `/api/songs` | ✅ Реалізовано |
| Читання пісні за ID | GET | `/api/songs/{id}` | ✅ Реалізовано |
| Створення пісні | POST | `/api/songs` | ✅ Реалізовано |
| Оновлення пісні | PUT | `/api/songs/{id}` | ✅ Реалізовано |
| Видалення пісні | DELETE | `/api/songs/{id}` | ✅ Реалізовано |

### ✅ Валідація

- ✅ Перевірка обов'язкових полів (title, artist, year, album, genre)
- ✅ Валідація року (1000-2100)
- ✅ Перевірка валідності JSON
- ✅ Обробка помилок з правильними HTTP кодами
- ✅ Очищення даних (trim) в усіх полях

### ✅ Обробка помилок

| Помилка | HTTP Код | Відповідь |
|---------|----------|----------|
| Невірний JSON | 400 | `{"error": "Невірний формат JSON"}` |
| Відсутні поля | 400 | `{"error": "Відсутні обов'язкові поля..."}` |
| Невірний рік | 400 | `{"error": "Рік повинен бути числом..."}` |
| Пісня не знайдена | 404 | `{"error": "Пісню не знайдено"}` |
| Серверна помилка | 500 | `{"error": "Помилка при..."}` |

### ✅ Анотації

Всі методи мають:
- PHP Attributes для маршрутизації (#[Route])
- Docblock коментарі для документування
- Опис параметрів та повертаємих значень
- Приклади запитів та відповідей

---

## 🏗️ Архітектура

### Song Entity (`src/Entity/Song.php`)
Основний клас для представлення пісні з методами:
- Getter/Setter для всіх полів
- `toArray()` - конвертація у масив
- `fromArray()` - створення з масиву

### SongController (`src/Controller/SongController.php`)
Основний контролер з 5 основними методами:
1. **index()** - GET `/api/songs` - отримати всі пісні
2. **show()** - GET `/api/songs/{id}` - отримати пісню за ID
3. **create()** - POST `/api/songs` - створити нову пісню
4. **update()** - PUT `/api/songs/{id}` - оновити пісню
5. **delete()** - DELETE `/api/songs/{id}` - видалити пісню

**Приватні методи:**
- `getSongsData()` - читання з JSON файлу
- `saveSongsData()` - запис у JSON файл

### SongManager Service (`src/Service/SongManager.php`)
Опціональний сервіс для управління даними (може використовуватися в інших місцях коду).

---

## 📊 Структура JSON файлу

```json
[
  {
    "id": "507f1f77bcf86cd799439011",
    "title": "Bohemian Rhapsody",
    "artist": "Queen",
    "year": 1975,
    "album": "A Night at the Opera",
    "genre": "Rock"
  },
  ...
]
```

**Поля:**
- `id` - автоматично генерується за допомогою `uniqid()`
- `title` - обов'язкове, строка
- `artist` - обов'язкове, строка
- `year` - обов'язкове, число 1000-2100
- `album` - обов'язкове, строка
- `genre` - обов'язкове, строка

---

## 🚀 Запуск проекту

### 1. Встановлення залежностей (якщо потрібні)
```bash
composer install
```

### 2. Запуск розробницького сервера
```bash
# Варіант 1: Symfony CLI
symfony serve

# Варіант 2: Вбудований PHP сервер
php -S localhost:8000 -t public
```

### 3. Перевірка
```bash
# Переконаємося, що API доступний
curl http://localhost:8000/api/songs
```

---

## 🧪 Тестування

### Варіант 1: Postman
1. Імпортуйте `songs-api.postman_collection.json`
2. Встановіть змінну `baseUrl = http://localhost:8000`
3. Виконайте тести

### Варіант 2: CURL
Див. файл `TEST_API.md` для всіх CURL команд.

### Варіант 3: PHP скрипт
```php
// Читання всіх пісень
$response = file_get_contents('http://localhost:8000/api/songs');
$songs = json_decode($response, true);
var_dump($songs);
```

---

## 📝 Документація

| Файл | Опис |
|------|------|
| [SONG_API_README.md](SONG_API_README.md) | Повна документація API з описом усіх операцій |
| [TEST_API.md](TEST_API.md) | Приклади тестування через CURL та PowerShell |
| [ANNOTATIONS_GUIDE.md](ANNOTATIONS_GUIDE.md) | Опис анотацій та синтаксису |
| [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) | Цей файл - огляд проекту |
| [songs-api.postman_collection.json](songs-api.postman_collection.json) | Postman колекція для тестування |

---

## 🔑 Ключові особливості реалізації

### 1. **JSON сховище замість БД**
```php
private function getSongsData(): array
{
    if (!file_exists($this->filePath)) {
        return [];
    }
    $content = file_get_contents($this->filePath);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}
```

### 2. **Обробка помилок на кожному кроці**
```php
if (json_last_error() !== JSON_ERROR_NONE) {
    return $this->json(['error' => 'Невірний формат JSON'], Response::HTTP_BAD_REQUEST);
}
```

### 3. **Валідація року**
```php
if (!is_numeric($data['year']) || (int)$data['year'] < 1000 || (int)$data['year'] > 2100) {
    return $this->json(['error' => 'Рік повинен бути числом від 1000 до 2100'], Response::HTTP_BAD_REQUEST);
}
```

### 4. **Try-catch для безпеки**
```php
try {
    $songs = $this->getSongsData();
    return $this->json($songs, Response::HTTP_OK);
} catch (\Exception $e) {
    return $this->json(['error' => 'Помилка при читанні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
}
```

### 5. **Использование PHP 8.2 синтаксиса**
```php
#[Route('/{id}', name: 'get_song', methods: ['GET'], requirements: ['id' => '[a-z0-9]+'])]
public function show(string $id): JsonResponse
```

---

## 🌟 Поліпшення в порівнянні з базовою версією

| Функція | Базова | Нова |
|---------|--------|-----|
| Валідація року | ❌ Ні | ✅ 1000-2100 |
| Обробка помилок | ❌ Базова | ✅ Детальна |
| Документація | ❌ Мінімальна | ✅ Повна |
| Анотації | ✅ Є | ✅ Розширені |
| Entity клас | ❌ Ні | ✅ Є |
| Postman колекція | ❌ Базова | ✅ Розширена |
| Тестові дані | ❌ Порожньо | ✅ 4 пісні |
| Test гайд | ❌ Ні | ✅ Детальний |

---

## 💻 Приклад запиту та відповіді

### Запит
```bash
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Bohemian Rhapsody",
    "artist": "Queen",
    "year": 1975,
    "album": "A Night at the Opera",
    "genre": "Rock"
  }'
```

### Відповідь (201 Created)
```json
{
  "id": "507f1f77bcf86cd799439015",
  "title": "Bohemian Rhapsody",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Rock"
}
```

---

## 🎓 Навчальне значення

Цей проект демонструє:
- ✅ RESTful API дизайн
- ✅ CRUD операції у Symfony
- ✅ JSON обробка у PHP
- ✅ Валідація та обробка помилок
- ✅ PHP 8.2 синтаксис
- ✅ Symfony 6.4 Attributes
- ✅ HTTP статус-коди
- ✅ Dokumentation best practices

---

## 🚀 Можливі розширення

1. **Пошук та фільтрація**
   ```
   GET /api/songs?artist=Queen&genre=Rock
   ```

2. **Сортування**
   ```
   GET /api/songs?sort=year&order=asc
   ```

3. **Пагінація**
   ```
   GET /api/songs?page=1&limit=10
   ```

4. **Перевірка дублів**
   - Запобігання створенню дублів пісень

5. **Аутентифікація**
   - API ключ або JWT токен

6. **Логування**
   - Запис всіх операцій у лог

7. **Кеширование**
   - Redis для оптимізації

8. **База даних**
   - Перехід з JSON на MySQL/PostgreSQL

---

## 📞 Контактна інформація

**Розробник:** BOGDANIST  
**Email:** bogddanist@gmail.com  
**Проект:** Song API CRUD  
**Версія:** 1.0.0  

---

## 📄 Ліцензія

Вільний для використання в навчальних та комерційних цілях.

---

**Все готово до використання! 🎉**
