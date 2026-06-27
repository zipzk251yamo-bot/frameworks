<?php

namespace App\Controller;

use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * SongController
 * API контролер для управління піснями через JSON файл.
 * Базовий URL: /api/songs
 */
#[Route('/api/songs')]
class SongController extends AbstractController
{
    private string $filePath;

    public function __construct(ParameterBagInterface $params)
    {
        $this->filePath = $params->get('kernel.project_dir') . '/var/songs.json';

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
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
     * @return JsonResponse Масив всіх пісень
     *
     * Приклад відповіді (200 OK):
     * [
     *   {
     *     "id": "507f1f77bcf86cd799439011",
     *     "title": "Bohemian Rhapsody",
     *     "artist": "Queen",
     *     "year": 1975,
     *     "album": "A Night at the Opera",
     *     "genre": "Rock"
     *   }
     * ]
     */
    #[Route('', name: 'get_all_songs', methods: ['GET'])]
    public function index(): JsonResponse
    {
        try {
            $songs = $this->getSongsData();
            return $this->json($songs, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Помилка при читанні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Отримує пісню за ID
     *
     * @param string $id ID пісні
     * @return JsonResponse Дані пісні або помилка 404
     *
     * Приклад відповіді (200 OK):
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
    #[Route('/{id}', name: 'get_song', methods: ['GET'], requirements: ['id' => '[a-z0-9]+'])]
    public function show(string $id): JsonResponse
    {
        try {
            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return $this->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            return $this->json($songs[$songIndex], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Помилка при читанні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Створює нову пісню
     *
     * @param Request $request JSON тіло з обов'язковими полями: title, artist, year, album, genre
     * @return JsonResponse Дані створеної пісні (201 Created) або помилка
     *
     * Приклад запиту (POST /api/songs):
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
     * Помилка (400 Bad Request):
     * {
     *   "error": "Невірний формат JSON"
     * }
     *
     * Помилка (400 Bad Request):
     * {
     *   "error": "Відсутні обов'язкові поля: title, artist, year, album, genre"
     * }
     */
    #[Route('', name: 'create_song', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json(['error' => 'Невірний формат JSON'], Response::HTTP_BAD_REQUEST);
            }

            if (empty($data['title']) || empty($data['artist']) || empty($data['year']) || empty($data['album']) || empty($data['genre'])) {
                return $this->json([
                    'error' => 'Відсутні обов\'язкові поля: title, artist, year, album, genre'
                ], Response::HTTP_BAD_REQUEST);
            }

            if (!is_numeric($data['year']) || (int)$data['year'] < 1000 || (int)$data['year'] > 2100) {
                return $this->json(['error' => 'Рік повинен бути числом від 1000 до 2100'], Response::HTTP_BAD_REQUEST);
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

            return $this->json($newSong, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Помилка при збереженні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Оновлює існуючу пісню (часткове оновлення)
     *
     * @param string $id ID пісні
     * @param Request $request JSON тіло з полями для оновлення (опціональні: title, artist, year, album, genre)
     * @return JsonResponse Оновлені дані пісні (200 OK) або помилка
     *
     * Приклад запиту (PUT /api/songs/{id}):
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
     * Помилка (404 Not Found):
     * {
     *   "error": "Пісню не знайдено"
     * }
     */
    #[Route('/{id}', name: 'update_song', methods: ['PUT'], requirements: ['id' => '[a-z0-9]+'])]
    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->json(['error' => 'Невірний формат JSON'], Response::HTTP_BAD_REQUEST);
            }

            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return $this->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            if (isset($data['year']) && (!is_numeric($data['year']) || (int)$data['year'] < 1000 || (int)$data['year'] > 2100)) {
                return $this->json(['error' => 'Рік повинен бути числом від 1000 до 2100'], Response::HTTP_BAD_REQUEST);
            }

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

            return $this->json($songs[$songIndex], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Помилка при оновленні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Видаляє пісню за ID
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
    #[Route('/{id}', name: 'delete_song', methods: ['DELETE'], requirements: ['id' => '[a-z0-9]+'])]
    public function delete(string $id): JsonResponse
    {
        try {
            $songs = $this->getSongsData();
            $songIndex = array_search($id, array_column($songs, 'id'));

            if ($songIndex === false) {
                return $this->json(['error' => 'Пісню не знайдено'], Response::HTTP_NOT_FOUND);
            }

            unset($songs[$songIndex]);
            $this->saveSongsData($songs);

            return $this->json(['message' => 'Пісню успішно видалено'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Помилка при видаленні даних'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
