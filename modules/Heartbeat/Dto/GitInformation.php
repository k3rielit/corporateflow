<?php

namespace Modules\Heartbeat\Dto;

use Illuminate\Support\Carbon;

class GitInformation
{

    public string|null $branch = null;
    public string|null $head = null;
    public Carbon|null $headModifiedAt = null;

    public string|null $headFile = null;
    public bool $headFileExists = false;

    public function __construct()
    {
        $this->headFile = base_path('.git/HEAD');
        $this->headFileExists = is_file($this->headFile);
    }

    public static function make(): static
    {
        return new static();
    }

    public function discover(): static
    {
        if (!$this->headFileExists) {
            return $this;
        }

        try {
            $headFileContent = file_get_contents($this->headFile);
        } catch (\Throwable $exception) {
            return $this;
        }
        preg_match('#^ref:(.+)$#', $headFileContent, $matches);
        $currentHead = trim($matches[1]);
        $currentHeadFile = base_path(".git/{$currentHead}");

        if ($currentHead && is_file($currentHeadFile)) {
            // If the file contains the path to the hash
            $this->branch = $currentHead;
            try {
                $this->head = trim(file_get_contents($currentHeadFile) ?? '');
                $this->headModifiedAt = Carbon::parse(filemtime($currentHeadFile) ?? 0);
            } catch (\Throwable $exception) {
            }
        } else if ($currentHead) {
            // When the file only contains the hash, not the path to the hash
            $this->head = $currentHead;
        }

        return $this;
    }

}
