<?php

namespace App\Models;

class Song
{
    public string $id;
    public string $title;
    public string $artist;
    public int $year;
    public string $album;
    public string $genre;

    public function __construct(
        string $title = '',
        string $artist = '',
        int $year = 0,
        string $album = '',
        string $genre = '',
        string $id = ''
    ) {
        $this->id = $id ?: uniqid();
        $this->title = $title;
        $this->artist = $artist;
        $this->year = $year;
        $this->album = $album;
        $this->genre = $genre;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'year' => $this->year,
            'album' => $this->album,
            'genre' => $this->genre,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? '',
            $data['artist'] ?? '',
            $data['year'] ?? 0,
            $data['album'] ?? '',
            $data['genre'] ?? '',
            $data['id'] ?? ''
        );
    }
}
