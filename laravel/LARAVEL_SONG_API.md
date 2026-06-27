# Laravel Song API CRUD - Документація

## 📋 Опис проекту

RESTful API для управління піснями з використанням **Laravel 8.2** та **PHP 8.2** з JSON файлом як сховище даних.

## 🏗️ Структура файлів

```
laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── SongController.php        # Основний контролер CRUD
│   └── Models/
│       └── Song.php                     # Model для пісні
├── routes/
│   └── api.php                          # API маршрути
├── storage/
│   └── app/
│       └── songs.json                   # JSON сховище даних
├── laravel-songs-api.postman_collection.json  # Postman колекція
└── LARAVEL_SONG_API.md                  # Ця документація
```

## 🎵 Структура пісні

```json
{
  "id": "507f1f77bcf86cd799439011",
  "title": "Bohemian Rhapsody",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Rock"
}
```

**Поля:**
- **id**: Унікальний ідентифікатор (генерується автоматично)
- **title**: Назва пісні (обов'язкове, строка)
- **artist**: Виконавець (обов'язкове, строка)
- **year**: Рік випуску (обов'язкове, число 1000-2100)
- **album**: Альбом (обов'язкове, строка)
- **genre**: Жанр (обов'язкове, строка)

---

## 🚀 API Endpoints

### 1. GET - Отримати всі пісні
```
GET /api/songs
```

**Відповідь (200 OK):**
```json
[
  {
    "id": "507f1f77bcf86cd799439011",
    "title": "Bohemian Rhapsody",
    "artist": "Queen",
    "year": 1975,
    "album": "A Night at the Opera",
    "genre": "Rock"
  }
]
```

**CURL приклад:**
```bash
curl -X GET http://localhost:8000/api/songs
```

---

### 2. GET - Отримати пісню за ID
```
GET /api/songs/{id}
```

**Параметри:**
- `{id}` - ID пісні

**Відповідь (200 OK):**
```json
{
  "id": "507f1f77bcf86cd799439011",
  "title": "Bohemian Rhapsody",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Rock"
}
```

**Помилка (404 Not Found):**
```json
{
  "error": "Пісню не знайдено"
}
```

**CURL приклад:**
```bash
curl -X GET http://localhost:8000/api/songs/507f1f77bcf86cd799439011
```

---

### 3. POST - Створити нову пісню
```
POST /api/songs
Content-Type: application/json
```

**Тіло запиту:**
```json
{
  "title": "Bohemian Rhapsody",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Rock"
}
```

**Відповідь (201 Created):**
```json
{
  "id": "507f1f77bcf86cd799439011",
  "title": "Bohemian Rhapsody",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Rock"
}
```

**Помилки:**

❌ **400 Bad Request** - Відсутні обов'язкові поля:
```json
{
  "error": "Відсутні обов'язкові поля: title, artist, year, album, genre"
}
```

❌ **400 Bad Request** - Невірний рік:
```json
{
  "error": "Рік повинен бути числом від 1000 до 2100"
}
```

**CURL приклад:**
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

---

### 4. PUT - Оновити пісню
```
PUT /api/songs/{id}
Content-Type: application/json
```

**Параметри:**
- `{id}` - ID пісні

**Тіло запиту (все опціонально):**
```json
{
  "title": "Bohemian Rhapsody - Remastered",
  "genre": "Progressive Rock"
}
```

**Відповідь (200 OK):**
```json
{
  "id": "507f1f77bcf86cd799439011",
  "title": "Bohemian Rhapsody - Remastered",
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
  "genre": "Progressive Rock"
}
```

**Помилки:**

❌ **404 Not Found** - Пісня не знайдена:
```json
{
  "error": "Пісню не знайдено"
}
```

❌ **400 Bad Request** - Невірний рік:
```json
{
  "error": "Рік повинен бути числом від 1000 до 2100"
}
```

**CURL приклад:**
```bash
curl -X PUT http://localhost:8000/api/songs/507f1f77bcf86cd799439011 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Bohemian Rhapsody - Remastered",
    "genre": "Progressive Rock"
  }'
```

---

### 5. DELETE - Видалити пісню
```
DELETE /api/songs/{id}
```

**Параметри:**
- `{id}` - ID пісні

**Відповідь (200 OK):**
```json
{
  "message": "Пісню успішно видалено"
}
```

**Помилка (404 Not Found):**
```json
{
  "error": "Пісню не знайдено"
}
```

**CURL приклад:**
```bash
curl -X DELETE http://localhost:8000/api/songs/507f1f77bcf86cd799439011
```

---

## 🧪 Тестування

### Використання Postman

1. Імпортуйте файл `laravel-songs-api.postman_collection.json` у Postman
2. Встановіть змінну `baseUrl` = `http://localhost:8000`
3. Для операцій зі специфічною піснею встановіть `songId` після першого створення

### Послідовність тестування:

1. **GET All Songs** - перевірка на список
2. **POST Create Song** - створення нової пісні (збережіть ID)
3. **GET Song by ID** - отримання пісні по ID
4. **PUT Update Song** - оновлення пісні
5. **DELETE Song** - видалення пісні

---

## 🚀 Запуск проекту

### 1. Встановлення залежностей (якщо потрібні)
```bash
composer install
```

### 2. Запуск розробницького сервера
```bash
php artisan serve
# Буде доступний на http://localhost:8000
```

### 3. Перевірка API
```bash
curl http://localhost:8000/api/songs
```

---

## 📝 Файли контролера

### SongController.php

**Методи:**
- `index()` - GET /api/songs - отримати всі пісні
- `show($id)` - GET /api/songs/{id} - отримати пісню за ID
- `store(Request $request)` - POST /api/songs - створити пісню
- `update($id, Request $request)` - PUT /api/songs/{id} - оновити пісню
- `destroy($id)` - DELETE /api/songs/{id} - видалити пісню

**Приватні методи:**
- `getSongsData()` - читання з JSON файлу
- `saveSongsData($data)` - запис у JSON файл
- `ensureDirectoryExists($path)` - створення директорії

### Song.php Model

Клас для представлення пісні з методами:
- Конструктор з параметрами
- `toArray()` - конвертація у масив
- `fromArray($data)` - створення з масиву

---

## ✅ Валідація та обробка помилок

### Валідація при створенні:
- ✅ Всі поля обов'язкові
- ✅ Рік повинен бути числом від 1000 до 2100
- ✅ Відстані синтаксис у полях (trim)

### HTTP статус-коди:
- **200 OK** - успішне читання/оновлення/видалення
- **201 Created** - успішне створення
- **400 Bad Request** - помилка в даних
- **404 Not Found** - пісню не знайдено
- **500 Internal Server Error** - внутрішня помилка

---

## 📂 JSON файл (storage/app/songs.json)

Файл автоматично створюється при першому звернення. Містить:

```json
[
  {
    "id": "507f1f77bcf86cd799439011",
    "title": "Bohemian Rhapsody",
    "artist": "Queen",
    "year": 1975,
    "album": "A Night at the Opera",
    "genre": "Rock"
  }
]
```

---

## 🔑 Ключові особливості

✅ **Повний CRUD** - Create, Read, Update, Delete  
✅ **JSON сховище** - storage/app/songs.json  
✅ **Валідація** - всіх параметрів  
✅ **Обробка помилок** - правильні HTTP коди  
✅ **Laravel 8.2** - сучасний фреймворк  
✅ **PHP 8.2** - найновіший синтаксис  
✅ **Анотації** - для документування  
✅ **Postman колекція** - готова для тестування  

---

## 💡 Приклади використання у PHP

### Отримання всіх пісень
```php
$response = file_get_contents('http://localhost:8000/api/songs');
$songs = json_decode($response, true);
foreach ($songs as $song) {
    echo $song['title'] . ' - ' . $song['artist'] . "\n";
}
```

### Створення пісні
```php
$newSong = [
    'title' => 'Bohemian Rhapsody',
    'artist' => 'Queen',
    'year' => 1975,
    'album' => 'A Night at the Opera',
    'genre' => 'Rock'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($newSong)
    ]
]);

$response = file_get_contents('http://localhost:8000/api/songs', false, $context);
$createdSong = json_decode($response, true);
echo 'Пісня створена з ID: ' . $createdSong['id'];
```

---

## 📧 Контакт

Розробник: BOGDANIST  
Email: bogddanist@gmail.com

---

**Все готово до використання! 🎉**
