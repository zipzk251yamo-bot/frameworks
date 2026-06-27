<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * SongController
 * API контролер для управління піснями через JSON файл
 * Базовий URL: /api/songs
 */
class SongController extends Controller
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/songs.json');

        if (!file_exists($this->filePath)) {
            $this->ensureDirectoryExists(dirname($this->filePath));
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    private function getSongsData(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $content = file_get_contents($this->filePath);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    private function saveSongsData(array $data): void
    {
        file_put_contents($this->filePath, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Отримує список усіх пісень
     *
     * GET /api/songs
     *
     * @return JsonResponse Масив всіх пісень
     */
    public function index(): JsonResponse
    {
        $songs = $this->getSongsData();
        return response()->json($songs);
    }

    /**
     * Отримує пісню за ID
     *
     * GET /api/songs/{id}
     *
     * @param string $id ID пісні
     * @return JsonResponse Дані пісні або помилка 404
     *
     * Приклад успішної відповіді (200 OK):
     * {
     *   "id": "507f1f77bcf86cd799439011",
     *   "title": "Bohemian Rhapsody",
     *   "artist": "Queen",
     *   "year": 1975,
     *   "album": "A Night at the Opera",
     *   "genre": "Rock"
     * }
     *
     * Помилка (404 Not Found):
     * {
     *   "error": "Пісню не знайдено"
     * }
     */
    public function show(string $id): JsonResponse
    {
        try {
            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return response()->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($songs[$songIndex], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка при читанні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Створює нову пісню
     *
     * POST /api/songs
     * Content-Type: application/json
     *
     * Тіло запиту з обов'язковими полями: title, artist, year, album, genre
     *
     * @param Request $request
     * @return JsonResponse Дані створеної пісні (201 Created) або помилка
     *
     * Приклад запиту:
     * {
     *   "title": "Bohemian Rhapsody",
     *   "artist": "Queen",
     *   "year": 1975,
     *   "album": "A Night at the Opera",
     *   "genre": "Rock"
     * }
     *
     * Успішна відповідь (201 Created):
     * {
     *   "id": "507f1f77bcf86cd799439011",
     *   "title": "Bohemian Rhapsody",
     *   "artist": "Queen",
     *   "year": 1975,
     *   "album": "A Night at the Opera",
     *   "genre": "Rock"
     * }
     *
     * Помилки:
     * 400 Bad Request - невірний JSON
     * 400 Bad Request - відсутні обов'язкові поля
     * 400 Bad Request - невірний рік (не в межах 1000-2100)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->json()->all();

            // Перевірка наявності обов'язкових полів
            if (empty($data['title']) || empty($data['artist']) || empty($data['year']) || empty($data['album']) || empty($data['genre'])) {
                return response()->json([
                    'error' => 'Відсутні обов\'язкові поля: title, artist, year, album, genre'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Валідація року
            if (!is_numeric($data['year']) || (int)$data['year'] < 1000 || (int)$data['year'] > 2100) {
                return response()->json(['error' => 'Рік повинен бути числом від 1000 до 2100'], Response::HTTP_BAD_REQUEST);
            }

            $songs = $this->getSongsData();

            $newSong = [
                'id' => uniqid(),
                'title' => trim($data['title']),
                'artist' => trim($data['artist']),
                'year' => (int) $data['year'],
                'album' => trim($data['album']),
                'genre' => trim($data['genre']),
            ];

            $songs[] = $newSong;
            $this->saveSongsData($songs);

            return response()->json($newSong, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка при збереженні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Оновлює існуючу пісню (часткове оновлення)
     *
     * PUT /api/songs/{id}
     * Content-Type: application/json
     *
     * @param string $id ID пісні
     * @param Request $request JSON тіло з полями для оновлення (опціональні)
     * @return JsonResponse Оновлені дані пісні (200 OK) або помилка
     *
     * Приклад запиту (часткове оновлення):
     * {
     *   "title": "Bohemian Rhapsody - Remastered"
     * }
     *
     * Успішна відповідь (200 OK):
     * {
     *   "id": "507f1f77bcf86cd799439011",
     *   "title": "Bohemian Rhapsody - Remastered",
     *   "artist": "Queen",
     *   "year": 1975,
     *   "album": "A Night at the Opera",
     *   "genre": "Rock"
     * }
     *
     * Помилки:
     * 404 Not Found - пісня не знайдена
     * 400 Bad Request - невірний JSON
     * 400 Bad Request - невірний рік
     */
    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = $request->json()->all();

            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return response()->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            // Валідація року якщо передано
            if (isset($data['year']) && (!is_numeric($data['year']) || (int)$data['year'] < 1000 || (int)$data['year'] > 2100)) {
                return response()->json(['error' => 'Рік повинен бути числом від 1000 до 2100'], Response::HTTP_BAD_REQUEST);
            }

            // Оновлюємо лише ті поля, які були передані
            if (isset($data['title'])) {
                $songs[$songIndex]['title'] = trim($data['title']);
            }
            if (isset($data['artist'])) {
                $songs[$songIndex]['artist'] = trim($data['artist']);
            }
            if (isset($data['year'])) {
                $songs[$songIndex]['year'] = (int) $data['year'];
            }
            if (isset($data['album'])) {
                $songs[$songIndex]['album'] = trim($data['album']);
            }
            if (isset($data['genre'])) {
                $songs[$songIndex]['genre'] = trim($data['genre']);
            }

            $this->saveSongsData($songs);

            return response()->json($songs[$songIndex], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка при оновленні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Видаляє пісню за ID
     *
     * DELETE /api/songs/{id}
     *
     * @param string $id ID пісні
     * @return JsonResponse Повідомлення про успіх (200 OK) або помилка
     *
     * Успішна відповідь (200 OK):
     * {
     *   "message": "Пісню успішно видалено"
     * }
     *
     * Помилка (404 Not Found):
     * {
     *   "error": "Пісню не знайдено"
     * }
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return response()->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            unset($songs[$songIndex]);
            $this->saveSongsData($songs);

            return response()->json(['message' => 'Пісню успішно видалено'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Помилка при видаленні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
