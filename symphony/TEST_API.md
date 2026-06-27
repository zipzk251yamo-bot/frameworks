# 🧪 Тестування Song API через CURL

Ці команди дозволяють вам протестувати всі операції API без Postman.

**Примітка:** Замініть `localhost:8000` на ваш URL, якщо сервер працює на іншому адресі.

---

## 1️⃣ Отримати всі пісні
```bash
curl -X GET http://localhost:8000/api/songs
```

**Очікуваний результат:** 200 OK з масивом всіх пісень

---

## 2️⃣ Створити нову пісню
```bash
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Stairway to Heaven",
    "artist": "Led Zeppelin",
    "year": 1971,
    "album": "Led Zeppelin IV",
    "genre": "Hard Rock"
  }'
```

**Очікуваний результат:** 201 Created з ID нової пісні
**Результат:** 
```json
{
  "id": "507f1f77bcf86cd799439015",
  "title": "Stairway to Heaven",
  "artist": "Led Zeppelin",
  "year": 1971,
  "album": "Led Zeppelin IV",
  "genre": "Hard Rock"
}
```

---

## 3️⃣ Отримати пісню за ID
```bash
curl -X GET http://localhost:8000/api/songs/507f1f77bcf86cd799439011
```

**Очікуваний результат:** 200 OK з даними конкретної пісні

---

## 4️⃣ Оновити пісню (часткове оновлення)
```bash
curl -X PUT http://localhost:8000/api/songs/507f1f77bcf86cd799439011 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Bohemian Rhapsody - Remastered",
    "genre": "Progressive Rock"
  }'
```

**Очікуваний результат:** 200 OK з оновленими даними

---

## 5️⃣ Видалити пісню
```bash
curl -X DELETE http://localhost:8000/api/songs/507f1f77bcf86cd799439011
```

**Очікуваний результат:** 200 OK з повідомленням про успіх

---

## ❌ Тестування помилок

### ❌ Помилка: Відсутні обов'язкові поля
```bash
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{"title": "Song Title"}'
```

**Очікуваний результат:** 400 Bad Request
```json
{
  "error": "Відсутні обов'язкові поля: title, artist, year, album, genre"
}
```

---

### ❌ Помилка: Невірний рік
```bash
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Song",
    "artist": "Test Artist",
    "year": 9999,
    "album": "Test Album",
    "genre": "Test Genre"
  }'
```

**Очікуваний результат:** 400 Bad Request
```json
{
  "error": "Рік повинен бути числом від 1000 до 2100"
}
```

---

### ❌ Помилка: Невірний JSON
```bash
curl -X POST http://localhost:8000/api/songs \
  -H "Content-Type: application/json" \
  -d '{ invalid json }'
```

**Очікуваний результат:** 400 Bad Request
```json
{
  "error": "Невірний формат JSON"
}
```

---

### ❌ Помилка: Пісня не знайдена
```bash
curl -X GET http://localhost:8000/api/songs/nonexistent123
```

**Очікуваний результат:** 404 Not Found
```json
{
  "error": "Пісню не знайдено"
}
```

---

## 🖥️ Команди для Windows PowerShell

### Отримати всі пісні
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/songs" -Method GET | ConvertFrom-Json
```

### Створити пісню
```powershell
$body = @{
    title = "Bohemian Rhapsody"
    artist = "Queen"
    year = 1975
    album = "A Night at the Opera"
    genre = "Rock"
} | ConvertTo-Json

Invoke-WebRequest -Uri "http://localhost:8000/api/songs" `
  -Method POST `
  -ContentType "application/json" `
  -Body $body | ConvertFrom-Json
```

### Отримати пісню за ID
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/songs/507f1f77bcf86cd799439011" `
  -Method GET | ConvertFrom-Json
```

### Оновити пісню
```powershell
$body = @{
    title = "Bohemian Rhapsody - Remastered"
    genre = "Progressive Rock"
} | ConvertTo-Json

Invoke-WebRequest -Uri "http://localhost:8000/api/songs/507f1f77bcf86cd799439011" `
  -Method PUT `
  -ContentType "application/json" `
  -Body $body | ConvertFrom-Json
```

### Видалити пісню
```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/songs/507f1f77bcf86cd799439011" `
  -Method DELETE | ConvertFrom-Json
```

---

## 📊 Тестовий сценарій (повна послідовність)

1. **Отримати усі пісні:**
   ```bash
   curl -X GET http://localhost:8000/api/songs
   ```

2. **Створити нову пісню та запам'ятати ID:**
   ```bash
   curl -X POST http://localhost:8000/api/songs \
     -H "Content-Type: application/json" \
     -d '{
       "title": "Imagine",
       "artist": "John Lennon",
       "year": 1971,
       "album": "Imagine",
       "genre": "Rock"
     }'
   ```
   *Результат містить ID, наприклад: `507f1f77bcf86cd799439015`*

3. **Отримати цю пісню:**
   ```bash
   curl -X GET http://localhost:8000/api/songs/507f1f77bcf86cd799439015
   ```

4. **Оновити пісню:**
   ```bash
   curl -X PUT http://localhost:8000/api/songs/507f1f77bcf86cd799439015 \
     -H "Content-Type: application/json" \
     -d '{"title": "Imagine - Remastered"}'
   ```

5. **Видалити пісню:**
   ```bash
   curl -X DELETE http://localhost:8000/api/songs/507f1f77bcf86cd799439015
   ```

6. **Перевірити, що пісня видалена:**
   ```bash
   curl -X GET http://localhost:8000/api/songs/507f1f77bcf86cd799439015
   ```
   *Повинна повернути 404 Not Found*

---

## 🔍 Перевірка JSON файлу

Всі дані зберігаються у файлі `var/songs.json`. Ви можете переглянути його:

```bash
# На Windows
type var\songs.json

# На Linux/Mac
cat var/songs.json
```

Або відредагувати його напряму для перевірки структури даних.

---

## 💡 Поради

1. **Зберегти ID для подальшого використання:**
   ```bash
   # Отримаємо ID першої пісні
   curl -s http://localhost:8000/api/songs | jq '.[0].id'
   ```

2. **Красиво вивести JSON:**
   ```bash
   # З jq (якщо встановлено)
   curl -s http://localhost:8000/api/songs | jq '.'
   
   # Або просто додати | json_pp у кінці (якщо встановлено)
   curl -s http://localhost:8000/api/songs | python -m json.tool
   ```

3. **Отримати тільки коди статусу:**
   ```bash
   curl -w "%{http_code}" -o /dev/null -s -X GET http://localhost:8000/api/songs
   ```
