<?php
namespace TransferPress\Services;

use ZipArchive;
use TransferPress\Models\PluginsFilesImport;
use TransferPress\Services\Helpers\FileSystemHelper;
class PluginsFilesImportService{
    public function importPlugin($zipFile): array
    {
       $fs = FileSystemHelper::getFilesystem();

        // Use WordPress's safe upload handling
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $uploaded = wp_handle_upload($zipFile, ['test_form' => false]);

        if (!empty($uploaded['error'])) {
            return ['success' => false, 'message' => 'Upload error: ' . $uploaded['error']];
        }
        $zipPath = $uploaded['file'];

        $model = new PluginsFilesImport();
        $pluginsDir = $model->getPluginsDir();
        $uploadDir = $model->getUploadDir();
        $tempDir = $uploadDir . 'temp-import';

        wp_mkdir_p($tempDir);

        // Step 2: Extract to a temporary sub-folder
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['success' => false, 'message' => 'Failed to open ZIP file.'];
        }

        $extractedTempPath = $tempDir . '/extracted';
        wp_mkdir_p($extractedTempPath);

        if (!$zip->extractTo($extractedTempPath)) {
            return ['success' => false, 'message' => 'Failed to extract ZIP file.'];
        }
        $zip->close();

        // Step 3: Determine folder name based on ZIP
        $zipName = pathinfo($zipFile['name'], PATHINFO_FILENAME);
        $finalPluginFolder = $pluginsDir . '/' . $zipName;

        // Step 4: Validate plugin (check for valid plugin header)
        $pluginRelativePath = $this->findMainPluginFile($extractedTempPath, $zipName);
        if (!$pluginRelativePath) {
            $fs->delete($zipPath);
            $model->deleteFolder($tempDir);
            return [
                'success' => false,
                'message' => 'Invalid plugin ZIP: No valid plugin file with proper header found.'
            ];
        }

        // Step 5: Delete existing plugin (if exists)
        if ($fs->is_dir($finalPluginFolder)) {
            $model->deleteFolder($finalPluginFolder); // already uses WP methods
        }

        // Step 6: Move validated contents into plugins directory
        wp_mkdir_p($finalPluginFolder);
        $model->moveFolderContents($extractedTempPath, $finalPluginFolder);

        // Step 7: Clean up temporary files
        $fs->delete($zipPath);
        $model->deleteFolder($tempDir);

        // Clear plugin cache so WP sees the new plugin
        wp_clean_plugins_cache(true);

        // Scan for main plugin file to activate it
        $pluginRelativePath = $this->findMainPluginFile($finalPluginFolder, $zipName);
        if ($pluginRelativePath) {
            activate_plugin($pluginRelativePath);
            $activated = true;
        } else {
            $activated = false;
        }

        return [
            'success' => true,
            'message' => 'Plugin imported ' . ($activated ? 'and activated' : 'but could not be auto-activated') . ' successfully.',
            'plugin_url' => admin_url('plugins.php')
        ];
    }
    private function findMainPluginFile(string $folderPath, string $folderName): ?string
    {
        $pluginFiles = glob($folderPath . '/*.php');
        foreach ($pluginFiles as $file) {
            $contents = file_get_contents($file);
            if (strpos($contents, 'Plugin Name:') !== false) {
                return basename($folderPath) . '/' . basename($file);
            }
        }
        return null;
    }
}
