<?php
namespace TransferPress\Models;

use TransferPress\Services\Helpers\FileSystemHelper;
class PluginsFilesImport{
    public function getPluginsDir(): string
    {
        return WP_PLUGIN_DIR;
    }

    public function getUploadDir(): string
    {
        $uploadDir = wp_upload_dir();
        return trailingslashit($uploadDir['basedir']) . 'transfer-press/';
    }

    public function deleteFolder(string $path): void
    {
        $fs = FileSystemHelper::getFilesystem();

        if ($fs->is_dir($path)) {
            $fs->delete($path, true); // recursive delete
        }
    }
    public function moveFolderContents($source, $destination): void
    {
        $fs = FileSystemHelper::getFilesystem();

        $items = $fs->dirlist($source);
        foreach ($items as $item => $details) {
            $src = trailingslashit($source) . $item;
            $dst = trailingslashit($destination) . $item;

            // move() is not always reliable; fallback to copy + delete
            if (!$fs->move($src, $dst)) {
                $fs->copy($src, $dst, true);  // true = overwrite
                $fs->delete($src, true);
            }
        }
    }
}
