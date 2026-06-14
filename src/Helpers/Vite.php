<?php

namespace Shika\Helpers;

/**
 * Helper for loading Vite built files using the manifest
 */
class Vite
{
    private string $basePath;
    private string $manifestPath;

    private ?array $manifest;
    private ?string $viteHost;

    public function __construct(string|null $viteHost)
    {
        $this->basePath = "build";
        $this->manifestPath = $this->basePath . "/manifest.json";
        $this->manifest = null;
        $this->viteHost = $viteHost;
    }

    /**
     * Gets a file from the manifest
     * 
     * @param string $name The file path
     * @return string The built file path
     */
    public function getFile(string $name): string
    {
        // When running in hot mode use the Vite development server host
        if ($this->isHot())
        {
            return $this->viteHost . "/" . $name;
        }

        $manifest = $this->getManifest();

        // Check if an entry exist
        if (!array_key_exists($name, $manifest))
        {
            throw new \Exception("No entry for $name in Vite manifest");
        }

        // Leading slash for routes not at root
        return "/" . $this->basePath . "/" . $manifest[$name]["file"];
    }

    /**
     * Gets whether the server is running in hot mode
     * @return bool Whether running in hot mode
     */
    public function isHot(): bool
    {
        return $this->viteHost !== null;
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