# 📝 Приклади анотацій у SongController

Контролер використовує **PHP Attributes** (новіший синтаксис Symfony 6.4) та **Docblock** коментарі для документування.

---

## 🏷️ Основні анотації (PHP Attributes)

### 1. Анотація класу контролера
```php
#[Route('/api/songs')]
class SongController extends AbstractController
```

**Пояснення:** Встановлює базовий маршрут для всіх методів контролера.

---

### 2. Анотація для методу: GET всі пісні
```php
#[Route('', name: 'get_all_songs', methods: ['GET'])]
public function index(): JsonResponse
```

**Параметри:**
- `''` - URL шлях (відносно класового маршруту)
- `name: 'get_all_songs'` - унікальне ім'я маршруту
- `methods: ['GET']` - HTTP метод

**Повна URL:** `GET /api/songs`

---

### 3. Анотація для методу: GET пісню за ID
```php
#[Route('/{id}', name: 'get_song', methods: ['GET'], requirements: ['id' => '[a-z0-9]+'])]
public function show(string $id): JsonResponse
```

**Параметри:**
- `/{id}` - динамічний параметр
- `requirements: ['id' => '[a-z0-9]+']` - регулярний вираз для валідації параметра

**Повна URL:** `GET /api/songs/{id}`

---

### 4. Анотація для методу: POST (створення)
```php
#[Route('', name: 'create_song', methods: ['POST'])]
public function create(Request $request): JsonResponse
```

**Повна URL:** `POST /api/songs`

---

### 5. Анотація для методу: PUT (оновлення)
```php
#[Route('/{id}', name: 'update_song', methods: ['PUT'], requirements: ['id' => '[a-z0-9]+'])]
public function update(string $id, Request $request): JsonResponse
```

**Повна URL:** `PUT /api/songs/{id}`

---

### 6. Анотація для методу: DELETE (видалення)
```php
#[Route('/{id}', name: 'delete_song', methods: ['DELETE'], requirements: ['id' => '[a-z0-9]+'])]
public function delete(string $id): JsonResponse
```

**Повна URL:** `DELETE /api/songs/{id}`

---

## 📚 Docblock коментарі (документація)

Перед кожним методом додані коментарі для документування:

### Формат
```php
/**
 * Опис того, що робить метод
 *
 * @param string $id ID пісні
 * @param Request $request JSON тіло запиту
 * @return JsonResponse Дані або повідомлення про помилку
 *
 * Детальний опис з прикладами...
 */
#[Route(...)]
public function methodName()
```

---

## 📋 Всі методи контролера з анотаціями

### 1. **index()** - Get All Songs
```php
/**
 * Отримує список усіх пісень
 *
 * @return JsonResponse Масив всіх пісень
 */
#[Route('', name: 'get_all_songs', methods: ['GET'])]
public function index(): JsonResponse
```

---

### 2. **show()** - Get Song by ID
```php
/**
 * Отримує пісню за ID
 *
 * @param string $id ID пісні
 * @return JsonResponse Дані пісні або помилка 404
 */
#[Route('/{id}', name: 'get_song', methods: ['GET'], requirements: ['id' => '[a-z0-9]+'])]
public function show(string $id): JsonResponse
```

---

### 3. **create()** - Create Song
```php
/**
 * Створює нову пісню
 *
 * @param Request $request JSON тіло з полями: title, artist, year, album, genre
 * @return JsonResponse Дані створеної пісні (201 Created) або помилка
 */
#[Route('', name: 'create_song', methods: ['POST'])]
public function create(Request $request): JsonResponse
```

---

### 4. **update()** - Update Song
```php
/**
 * Оновлює існуючу пісню (часткове оновлення)
 *
 * @param string $id ID пісні
 * @param Request $request JSON тіло з полями для оновлення
 * @return JsonResponse Оновлені дані пісні (200 OK) або помилка
 */
#[Route('/{id}', name: 'update_song', methods: ['PUT'], requirements: ['id' => '[a-z0-9]+'])]
public function update(string $id, Request $request): JsonResponse
```

---

### 5. **delete()** - Delete Song
```php
/**
 * Видаляє пісню за ID
 *
 * @param string $id ID пісні
 * @return JsonResponse Повідомлення про успіх (200 OK) або помилка
 */
#[Route('/{id}', name: 'delete_song', methods: ['DELETE'], requirements: ['id' => '[a-z0-9]+'])]
public function delete(string $id): JsonResponse
```

---

## 🔍 Параметри Route Attribute

| Параметр | Опис | Приклад |
|----------|------|---------|
| `''` або `'/path'` | URL маршрут | `#[Route('/api/songs')]` |
| `name` | Унікальне ім'я маршруту | `name: 'get_all_songs'` |
| `methods` | HTTP методи | `methods: ['GET', 'POST']` |
| `requirements` | Регулярні вирази для параметрів | `requirements: ['id' => '[a-z0-9]+']` |

---

## 🎯 Конвенції іменування

| Метод | Ім'я | HTTP | URL |
|-------|------|------|-----|
| index() | get_all_songs | GET | /api/songs |
| show() | get_song | GET | /api/songs/{id} |
| create() | create_song | POST | /api/songs |
| update() | update_song | PUT | /api/songs/{id} |
| delete() | delete_song | DELETE | /api/songs/{id} |

---

## 📦 Типи return значень

Усі методи контролера повертають `JsonResponse`:

```php
// Успіх (200 OK)
return $this->json($data, Response::HTTP_OK);

// Створено (201 Created)
return $this->json($data, Response::HTTP_CREATED);

// Помилка (400 Bad Request)
return $this->json(['error' => 'Помилка'], Response::HTTP_BAD_REQUEST);

// Не знайдено (404 Not Found)
return $this->json(['error' => 'Не знайдено'], Response::HTTP_NOT_FOUND);

// Серверна помилка (500 Internal Server Error)
return $this->json(['error' => 'Помилка'], Response::HTTP_INTERNAL_SERVER_ERROR);
```

---

## 🔗 HTTP статус-коди, що використовуються

| Код | Значення | Коли використовується |
|-----|----------|----------------------|
| **200** | OK | Успішне читання/оновлення/видалення |
| **201** | Created | Успішне створення нового ресурсу |
| **400** | Bad Request | Помилка в даних (невірний JSON, відсутні поля) |
| **404** | Not Found | Ресурс не знайдено |
| **500** | Internal Server Error | Внутрішня помилка сервера |

---

## 💡 PHP 8.2 синтаксис

Контролер використовує сучасний PHP 8.2 синтаксис:

### Type hints (типізація)
```php
public function show(string $id): JsonResponse
                     ^^^^^^      ^^^^^^^^^^^^^^
                  параметр      тип повернення
```

### Match expression (замість switch)
```php
$status = match($code) {
    200 => 'OK',
    201 => 'Created',
    400 => 'Bad Request',
    404 => 'Not Found',
    default => 'Unknown'
};
```

### Named arguments (іменовані аргументи)
```php
#[Route(
    path: '/api/songs',
    name: 'get_all_songs',
    methods: ['GET']
)]
```

### Union types (об'єднані типи)
```php
public function process(string|int|null $value): string|null
```

---

## 📖 Приклади використання з Symfony

### Генерування URL за ім'ям маршруту
```php
$url = $this->generateUrl('get_song', ['id' => '507f1f77bcf86cd799439011']);
// Результат: /api/songs/507f1f77bcf86cd799439011
```

### Перенаправлення на маршрут
```php
return $this->redirectToRoute('get_all_songs');
```

### Перевірка методу запиту
```php
if ($request->isMethod('POST')) {
    // Обробка POST запиту
}
```

---

## 🚀 Як додати нові маршрути

Щоб додати новий API endpoint, додайте новий метод до контролера:

```php
/**
 * Отримує пісні за жанром
 *
 * @param string $genre Жанр для пошуку
 * @return JsonResponse Масив пісень заданого жанру
 */
#[Route('/by-genre/{genre}', name: 'get_songs_by_genre', methods: ['GET'])]
public function getByGenre(string $genre): JsonResponse
{
    $songs = $this->getSongsData();
    $filtered = array_filter($songs, fn($song) => strtolower($song['genre']) === strtolower($genre));
    return $this->json(array_values($filtered), Response::HTTP_OK);
}
```

**Повна URL:** `GET /api/songs/by-genre/{жанр}`

**Приклад:** `GET /api/songs/by-genre/Rock`
