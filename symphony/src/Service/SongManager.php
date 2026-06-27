<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * SongManager Service для управління піснями через JSON файл
 */
class SongManager
{
    private string $dataFile;

    public function __construct(ParameterBagInterface $params)
    {
        $dataDir = $params->get('kernel.project_dir') . '/var/data';

        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        $this->dataFile = $dataDir . '/songs.json';

        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    /**
     * Читає всі пісні з JSON файлу
     */
    private function readSongs(): array
    {
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?? [];
    }

    /**
     * Записує пісні у JSON файл
     */
    private function writeSongs(array $songs): void
    {
        file_put_contents($this->dataFile, json_encode($songs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Отримує все пісні
     */
    public function getAllSongs(): array
    {
        return $this->readSongs();
    }

    /**
     * Отримує пісню за ID
     */
    public function getSongById(string $id): ?array
    {
        $songs = $this->readSongs();
        foreach ($songs as $song) {
            if ($song['id'] === $id) {
                return $song;
            }
        }
        return null;
    }

    /**
     * Створює нову пісню
     */
    public function createSong(array $data): array
    {
        $songs = $this->readSongs();

        $newSong = [
            'id' => uniqid(),
            'title' => trim($data['title']),
            'artist' => trim($data['artist']),
            'year' => (int)$data['year'],
            'album' => trim($data['album'] ?? ''),
            'genre' => trim($data['genre'] ?? ''),
        ];

        $songs[] = $newSong;
        $this->writeSongs($songs);

        return $newSong;
    }

    /**
     * Оновлює пісню за ID (часткове оновлення)
     */
    public function updateSong(string $id, array $data): ?array
    {
        $songs = $this->readSongs();

        foreach ($songs as &$song) {
            if ($song['id'] === $id) {
                if (isset($data['title'])) {
                    $song['title'] = trim($data['title']);
                }
                if (isset($data['artist'])) {
                    $song['artist'] = trim($data['artist']);
                }
                if (isset($data['year'])) {
                    $song['year'] = (int)$data['year'];
                }
                if (isset($data['album'])) {
                    $song['album'] = trim($data['album']);
                }
                if (isset($data['genre'])) {
                    $song['genre'] = trim($data['genre']);
                }

                $this->writeSongs($songs);
                return $song;
            }
        }

        return null;
    }

    /**
     * Видаляє пісню за ID
     */
    public function deleteSong(string $id): bool
    {
        $songs = $this->readSongs();

        foreach ($songs as $key => $song) {
            if ($song['id'] === $id) {
                unset($songs[$key]);
                $this->writeSongs(array_values($songs));
                return true;
            }
        }

        return false;
    }
}
