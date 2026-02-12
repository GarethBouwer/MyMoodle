<?php
// Minimal stubs to help static analysis tools (Intelephense, PHPStan, etc.)
// These are intentionally small and only used for IDE analysis, not executed.

interface renderable {}
interface templatable {}
class renderer_base {}

class moodle_url {
    public function __construct($path = '', $params = []) {}
    public function out() {}
}

class single_button {
    const BUTTON_PRIMARY = 1;
    public function __construct($url, $label = '', $method = 'get', $extra = null) {}
    public function export_for_template($renderer) {}
}

class moodle_exception extends \Exception {
    public $errorcode;
}

class html_writer {
    public static function span($text, $class = '') { return '<span class="' . $class . '">' . $text . '</span>'; }
}

class cm_info {
    public static function create($cm) {}
}

class core_component {
    public static function get_plugin_list($type) { return []; }
}
