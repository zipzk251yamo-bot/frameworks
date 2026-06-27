# Song API CRUD - Повна документація

## 📋 Опис проекту
RESTful API для управління піснями з використанням **Symfony 6.4**, **PHP 8.2** та **JSON файлу** як сховища даних замість БД.

## 🏗️ Структура проекту

```
src/
├── Controller/
│   └── SongController.php      # Основний контролер з CRUD операціями
├── Entity/
│   └── Song.php               # Entity клас для пісні
└── Service/
    └── SongManager.php        # Сервіс для управління даними (опціонально)

var/
└── songs.json                 # JSON файл для зберігання даних
```

## 🎵 Структура пісні (Song)

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

### Поля:
- **id** (string): Унікальний ідентифікатор (генерується автоматично)
- **title** (string): Назва пісні **(обов'язкове)**
- **artist** (string): Виконавець **(обов'язкове)**
- **year** (integer): Рік випуску 1000-2100 **(обов'язкове)**
- **album** (string): Альбом **(обов'язкове)**
- **genre** (string): Жанр **(обов'язкове)**

## 🚀 API Endpoints

### 1️⃣ Отримати всі пісні (GET)
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

### 2️⃣ Отримати пісню за ID (GET)
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

### 3️⃣ Створити нову пісню (POST)
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

❌ **400 Bad Request** - Невірний JSON:
```json
{
  "error": "Невірний формат JSON"
}
```

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

### 4️⃣ Оновити пісню (PUT)
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
  "artist": "Queen",
  "year": 1975,
  "album": "A Night at the Opera",
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

❌ **400 Bad Request** - Невірний JSON:
```json
{
  "error": "Невірний формат JSON"
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

### 5️⃣ Видалити пісню (DELETE)
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
1. Імпортуйте файл `songs-api.postman_collection.json` у Postman
2. Встановіть змінну `baseUrl` = `http://127.0.0.1:8000`
3. Для тестування операцій зі специфічною піснею встановіть `songId` після першого створення

### Послідовність тестування:
1. ✅ **Get All Songs** - перевірка на пусту колекцію
2. ✅ **Create Song** - створення нової пісні (збережіть ID)
3. ❌ **Create Song - Missing Fields** - тестування помилки
4. ✅ **Get Song by ID** - отримання пісні по ID
5. ✅ **Update Song** - оновлення пісні
6. ✅ **Delete Song** - видалення пісні

---

## 📝 Приклади використання в PHP

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

### Оновлення пісні
```php
$update = ['title' => 'Bohemian Rhapsody - Remastered'];

$context = stream_context_create([
    'http' => [
        'method' => 'PUT',
        'header' => 'Content-Type: application/json',
        'content' => json_encode($update)
    ]
]);

$response = file_get_contents('http://localhost:8000/api/songs/SONG_ID', false, $context);
$updatedSong = json_decode($response, true);
```

### Видалення пісні
```php
$context = stream_context_create([
    'http' => ['method' => 'DELETE']
]);

$response = file_get_contents('http://localhost:8000/api/songs/SONG_ID', false, $context);
$result = json_decode($response, true);
```

---

## 🔒 Валідація та обробка помилок

### Валідація при створенні:
- ✅ Всі поля обов'язкові
- ✅ Рік повинен бути числом від 1000 до 2100
- ✅ JSON повинен бути правильно сформований
- ✅ Відстані синтаксис у полях (trim)

### HTTP статус-коди:
- **200 OK** - успішне читання/оновлення/видалення
- **201 Created** - успішне створення
- **400 Bad Request** - помилка в даних (невірний JSON, відсутні поля, невірна валідація)
- **404 Not Found** - пісню не знайдено
- **500 Internal Server Error** - внутрішня помилка сервера

---

## 📂 JSON файл (var/songs.json)

Файл автоматично створюється у папці `var/` при першому звернення. Змістовуватиме:

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
  {
    "id": "507f1f77bcf86cd799439012",
    "title": "Imagine",
    "artist": "John Lennon",
    "year": 1971,
    "album": "Imagine",
    "genre": "Rock"
  }
]
```

---

## 🚀 Запуск проекту

```bash
# Встановлення залежностей (якщо потрібні)
composer install

# Запуск розробницького сервера
symfony serve
# або
php -S localhost:8000 -t public

# API буде доступний за адресою:
http://localhost:8000/api/songs
```

---

## 🔑 Ключові особливості

✅ **Повний CRUD** - Create, Read, Update, Delete  
✅ **JSON сховище** - без необхідності БД  
✅ **Валідація** - перевірка всіх параметрів  
✅ **Обробка помилок** - правильні HTTP коди  
✅ **Symfony 6.4** - сучасний PHP фреймворк  
✅ **PHP 8.2** - найновіший синтаксис PHP  
✅ **Анотації (Attributes)** - для маршрутизації  
✅ **JSON Response** - для всіх операцій  
✅ **Postman колекція** - готова для тестування  

---

## 📧 Контакт
Розробивач: BOGDANIST  
Email: bogddanist@gmail.com
