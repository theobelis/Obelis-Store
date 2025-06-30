<?php
class Assets {
    private static $css = [];
    private static $js = [];
    
    public static function addCss($path) {
        if (!in_array($path, self::$css)) {
            self::$css[] = $path;
        }
    }
    
    public static function addJs($path) {
        if (!in_array($path, self::$js)) {
            self::$js[] = $path;
        }
    }
    
    public static function renderCss() {
        foreach (self::$css as $css) {
            echo '<link rel="stylesheet" href="' . BASE_URL . $css . '">';
        }
    }
    
    public static function renderJs() {
        foreach (self::$js as $js) {
            echo '<script src="' . BASE_URL . $js . '"></script>';
        }
    }
}
