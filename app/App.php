<?php
namespace TransferPress;

use TransferPress\Hooks\Handlers\TranprAdminMenuHandlers;
use TransferPress\Http\Controllers\TranprPluginsExportController;
use TransferPress\Http\Controllers\TranprPluginsImportController;

class App {

    public function __construct() {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        $this->defineConstant();

       //Load Classes
       (new TranprAdminMenuHandlers())->init();
        new TranprPluginsExportController();
        new TranprPluginsImportController();
    }
    private function defineConstant(): void
    {
        define( 'TRANPR', 'transfer-press' );
        define( 'TRANPR_PATH', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
        define( 'TRANPR_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
        define( 'TRANPR_BUILD_PATH', TRANPR_PATH . '/public/assets' );
        define( 'TRANPR_BUILD_URL', TRANPR_URL . '/public/assets' );
        define( 'TRANPR_VERSION', '1.0.0' );
    }
}
