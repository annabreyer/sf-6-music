<?php declare(strict_types = 1);

namespace App\Service;

class AppleMusicPlaylistService
{

    public const PLAYLIST_HEADERS = [
        'Name',
        'Artist',
        'Composer',
        'Album',
        'Grouping',
        'Work',
        'Movement Number',
        'Movement Count',
        'Movement Name',
        'Genre',
        'Size',
        'Time',
        'Disc Number',
        'Disc Count',
        'Track Number',
        'Track Count',
        'Year',
        'Date Modified',
        'Date Added',
        'Bit Rate',
        'Sample Rate',
        'Volume Adjustment',
        'Kind',
        'Equaliser',
        'Comments',
        'Plays',
        'Last Played',
        'Skips',
        'Last Skipped',
        'My Rating',
        'Location',
    ];

    public function loadPlaylist(string $path): array
    {
        $input    = $this->getInput($path);
        $playlist = [];

        foreach ($input as $songKey => $song) {
            foreach ($song as $key => $data) {
                $playlist[$songKey][self::PLAYLIST_HEADERS[$key]] = $data;
            }
        }

        return $playlist;
    }

    public function getInput(string $filePath): array
    {
        $inputs  = [];
        $content = \fopen($filePath, 'r');

        while (($line = \fgets($content)) !== false) {
            $data     = mb_convert_encoding($line, 'UTF-8');
            $inputs[] = \explode("\t", $data);
        }

        \fclose($content);

        array_splice($inputs, 0, 1);

        return array_values($inputs);
    }
}
