<?php

namespace Shika\Helpers;

/**
 * Helper for loading Vite built files using the manifest
 */
class Vite
{
    private string $manifestPath;
    private string $basePath;
    private ?array $manifest;

    public function __construct()
    {
        $this->basePath = "build";
        $this->manifestPath = $this->basePath . "/manifest.json";
        $this->manifest = null;
    }

    /**
     * Gets a file from the manifest
     * 
     * @param   string  $name   The file path
     * @return  string  The built file path
     */
    public function getFile(string $name)
    {
        $manifest = $this->getManifest();

        // Check if an entry exist
        if (!array_key_exists($name, $manifest))
        {
            throw new \Exception("No entry for $name in Vite manifest");
        }

        return $this->basePath . "/" . $manifest[$name]["file"];
    }

    private function getManifest(): mixed
    {
        if ($this->manifest === null)
        {
            // Load the manifest
            if (!file_exists($this->manifestPath))
            {
                throw new \Exception("Failed to load Vite manifest");
            }

            $this->manifest = json_decode(file_get_contents($this->manifestPath), true);
        }

        return $this->manifest;
    }
}