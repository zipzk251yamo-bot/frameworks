<?php

namespace App\Entity;

class Song
{
    private string $id;
    private string $title;
    private string $artist;
    private int $year;
    private string $album;
    private string $genre;

    public function __construct(
        string $title,
        string $artist,
        int $year,
        string $album,
        string $genre,
        string $id = ''
    ) {
        $this->id = $id ?: uniqid();
        $this->title = $title;
        $this->artist = $artist;
        $this->year = $year;
        $this->album = $album;
        $this->genre = $genre;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;
        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getAlbum(): string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->album = $album;
        return $this;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
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
            $data['title'],
            $data['artist'],
            $data['year'],
            $data['album'],
            $data['genre'],
            $data['id'] ?? ''
        );
    }
}
