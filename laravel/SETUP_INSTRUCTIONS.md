# Інструкції по встановленню Laravel Song API

## 🚀 Швидкий старт

### 1. Встановлення залежностей
```bash
cd laravel
composer install
```

### 2. Копіювання .env файлу
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Запуск сервера
```bash
php artisan serve
```

API буде доступний на: **http://localhost:8000/api/songs**

---

## 📝 Поточна структура файлів

Контролер вже створений:
- `app/Http/Controllers/SongController.php` - основний контролер
- `app/Models/Song.php` - модель пісні
- `routes/api.php` - маршрути API
- `storage/app/songs.json` - JSON сховище

---

## ✅ Тестування API

### Варіант 1: CURL команди

```bash
# Отримати всі пісні
curl -X GET http://localhost:8000/api/songs

# Отримати пісню за ID
curl -X GET http://localhost:8000/api/songs/507f1f77bcf86cd799439011

# Створити нову пісню
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Song",
    "artist": "Test Artist",
    "year": 2023,
    "album": "Test Album",
    "genre": "Rock"
  }'

# Оновити пісню
curl -X PUT http://localhost:8000/api/songs/507f1f77bcf86cd799439011 \
  -H "Content-Type: application/json" \
  -d '{"title": "Updated Title"}'

# Видалити пісню
curl -X DELETE http://localhost:8000/api/songs/507f1f77bcf86cd799439011
```

### Варіант 2: Postman

1. Імпортуйте `laravel-songs-api.postman_collection.json`
2. Встановіть `baseUrl = http://localhost:8000`
3. Запустіть тести

### Варіант 3: Laravel Tinker

```bash
php artisan tinker

# Тестування функцій
$songs = json_decode(file_get_contents(storage_path('app/songs.json')), true);
dd($songs);
```

---

## 🔍 Деталі маршрутів

| Метод | Маршрут | Функція | Опис |
|-------|---------|---------|------|
| GET | /api/songs | index | Отримати всі пісні |
| GET | /api/songs/{id} | show | Отримати пісню за ID |
| POST | /api/songs | store | Створити нову пісню |
| PUT | /api/songs/{id} | update | Оновити пісню |
| DELETE | /api/songs/{id} | destroy | Видалити пісню |

Всі маршрути визначені в `routes/api.php`

---

## 📊 Структура JSON файлу

Файл: `storage/app/songs.json`

```json
[
  {
    "id": "507f1f77bcf86cd799439011",
    "title": "Song Title",
    "artist": "Artist Name",
    "year": 2023,
    "album": "Album Name",
    "genre": "Genre"
  }
]
```

Файл створюється автоматично при першому звернені до контролера.

---

## 🛠️ Налаштування CORS (якщо потрібно)

Якщо отримуєте помилки CORS, відредагуйте `config/cors.php`:

```php
'allowed_origins' => ['*'], // Дозволити всім
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Content-Type'],
```

Або встановіть пакет:
```bash
composer require fruitcake/laravel-cors
```

---

## 🧪 Тестові дані

В файлі `storage/app/songs.json` вже присутні 4 тестові пісні:
1. Bohemian Rhapsody - Queen
2. Imagine - John Lennon
3. Like a Rolling Stone - Bob Dylan
4. Stairway to Heaven - Led Zeppelin

Можете їх видалити та тестувати з чистого стану.

---

## 📦 Вимоги

- PHP 8.2 або вище
- Laravel 8.2
- Composer
- Права на запис у папку `storage/`

---

## 🐛 Розв'язання проблем

### Помилка: "storage/app не існує"
```bash
mkdir -p storage/app
chmod -R 755 storage/
```

### Помилка: "Не можу записати у файл"
```bash
chmod -R 777 storage/
```

### Помилка 419 (Token Mismatch)
Це CSRF помилка. Для API запитів додайте заголовок:
```
X-CSRF-TOKEN: {{csrf_token}}
```

Але для публічного API це зазвичай не потрібно, якщо маршрути в `routes/api.php`.

---

## 📝 Документація

- Повна документація: [LARAVEL_SONG_API.md](LARAVEL_SONG_API.md)
- Postman колекція: [laravel-songs-api.postman_collection.json](laravel-songs-api.postman_collection.json)

---

## 🎯 Структура проекту

```
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── SongController.php
│   │   └── Middleware/
│   └── Models/
│       └── Song.php
├── routes/
│   ├── api.php (маршрути API)
│   └── web.php
├── storage/
│   └── app/
│       └── songs.json
├── config/
├── public/
├── .env
├── composer.json
├── LARAVEL_SONG_API.md
├── SETUP_INSTRUCTIONS.md
└── laravel-songs-api.postman_collection.json
```

---

## 💡 Корисні команди

```bash
# Запуск сервера
php artisan serve

# Запуск на специфічному портові
php artisan serve --port=8001

# Очистити кеш
php artisan cache:clear

# Переглянути всі маршрути
php artisan route:list | grep api

# Запустити Tinker (інтерактивна консоль)
php artisan tinker
```

---

## 🚀 Розгортання

### На виробництво

1. Встановіть залежності:
   ```bash
   composer install --no-dev
   ```

2. Скопіюйте `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Встановіть права на папку:
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

4. Настройте вебсервер (Nginx/Apache) щоб вказував на `public/` папку

---

## 📧 Контакт

Разработник: BOGDANIST  
Email: bogddanist@gmail.com

---

**Усе готово! Починайте тестувати API 🎉**
