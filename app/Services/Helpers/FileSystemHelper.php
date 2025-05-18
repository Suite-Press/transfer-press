<?php
namespace TransferPress\Services\Helpers;

class FileSystemHelper{
    public static function getFilesystem() {
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        return $wp_filesystem;
    }
}
