<?php

namespace App\Storage;


class LocalFileStorage
{
    /**
     * @param string $baseDirectory Root directory for file storage
     * @param string $baseUrl       Base URL for public access
     */
    public function __construct(
        private readonly string $baseDirectory,
        private readonly string $baseUrl,
    ) {
    }

    /**
     * Stores a file in the local filesystem
     *
     * @param string $path    Target path relative to base directory
     * @param string $content File content to store
     */
    public function store(string $path, string $content): void
    {
        $fullPath  = $this->getFullPath($path);
        $directory = dirname($fullPath);

        // Create directory structure if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($fullPath, $content);
    }

    /**
     * Deletes a file from local filesystem if it exists
     *
     * @param string $path Path to file relative to base directory
     */
    public function delete(string $path): void
    {
        $fullPath = $this->getFullPath($path);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Checks if file exists in local filesystem
     *
     * @param string $path Path to check relative to base directory
     *
     * @return bool True if file exists
     */
    public function exists(string $path): bool
    {
        return file_exists($this->getFullPath($path));
    }

    /**
     * Gets public URL for accessing the file
     *
     * @param string $path File path relative to base directory
     *
     * @return string Complete public URL
     */
    public function getUrl(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * Builds complete filesystem path from relative path
     *
     * @param string $path Relative path
     *
     * @return string Complete filesystem path
     */
    private function getFullPath(string $path): string
    {
        return $this->baseDirectory . '/' . ltrim($path, '/');
    }
}
