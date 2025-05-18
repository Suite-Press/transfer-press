<?php
namespace TransferPress\Services;

use TransferPress\Services\Helpers\FileSystemHelper;
use ZipArchive;
use TransferPress\Models\PluginsFilesExport;
class PluginsFilesExportService{

    protected $fs;
    public function __construct() {
        $this->fs = FileSystemHelper::getFilesystem();
    }
    public function exportPluginFiles(string $pluginSlug): bool|string
    {
        $model = new PluginsFilesExport();
        $pluginPath = $model->getPluginPath($pluginSlug);

        $uploadDir = wp_upload_dir();
        $pluginDirName = explode('/', $pluginSlug)[0]; // only folder name
        $exportBaseDir = trailingslashit($uploadDir['basedir']) . 'transfer-press/';
        $workingDir = $exportBaseDir . $pluginDirName . '-export';
        $zipPath = $exportBaseDir . $pluginDirName . '.zip';

        // Ensure export base dir exists
        wp_mkdir_p($exportBaseDir);

        // Clean up previous exports
        if ($this->fs->is_dir($workingDir)) {
            $this->fs->delete($workingDir, true);
        }
        if ($this->fs->exists($zipPath)) {
            $this->fs->delete($zipPath);
        }

        wp_mkdir_p($workingDir);

        // Copy plugin files to working directory
        if (!copy_dir($pluginPath, $workingDir)) {
            return false;
        }

        // Create ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $this->folderToZip($workingDir, $zip, strlen($workingDir) + 1);
            $zip->close();
        } else {
            return false;
        }

        // Clean up working directory
        $this->fs->delete($workingDir, true);

        return trailingslashit($uploadDir['baseurl']) . 'transfer-press/' . $pluginDirName . '.zip';
    }

    public function folderToZip($folder, &$zipFile, $exclusiveLength) {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $filePath = $file->getRealPath();
            $localPath = substr($filePath, $exclusiveLength);
            if ($file->isDir()) {
                $zipFile->addEmptyDir($localPath);
            } else {
                $zipFile->addFile($filePath, $localPath);
            }
        }
    }
}
