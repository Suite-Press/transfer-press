<?php
namespace TransferPress\Models;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
class TranprPluginsExport{
    public function getInstalledPlugins(): array
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        return get_plugins();
    }
    public function getPluginPath(string $pluginSlug): string
    {
        $allPlugins = get_plugins();
        foreach ($allPlugins as $path => $pluginData) {
            $dir = dirname($path); // e.g., transfer-press
            if ($path === $pluginSlug || $dir === $pluginSlug) {
                return WP_PLUGIN_DIR . '/' . $dir;
            }
        }
        return '';
    }
    public function getPluginDetails(string $pluginSlug): array {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $allPlugins = get_plugins();

        if (!isset($allPlugins[$pluginSlug])) {
            return [];
        }

        $pluginData = $allPlugins[$pluginSlug];
        $path = $this->getPluginPath($pluginSlug);
        $isActive = is_plugin_active($pluginSlug);

        return [
            'name'        => $pluginData['Name'],
            'slug'        => $pluginSlug,
            'version'     => $pluginData['Version'],
            'description' => $pluginData['Description'],
            'author'      => $pluginData['Author'],
            'active'      => $isActive,
            'path'        => $path,
            'size'        => size_format($this->getDirectorySize($path), 2),
        ];
    }

    private function getDirectorySize(string $path): int {
        $size = 0;
        if (!is_dir($path)) return 0;

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }
}
