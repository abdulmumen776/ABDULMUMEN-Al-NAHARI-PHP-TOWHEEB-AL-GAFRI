<?php

namespace Componst;

class ComponentStyles
{
    private static bool $injected = false;

    /**
     * Returns a <style> tag with all component styles and guarantees we only print it once.
     */
    public static function inject(): string
    {
        if (self::$injected) {
            return '';
        }

        $cssPath = __DIR__ . '/components.css';
        $css = is_file($cssPath) ? trim((string) file_get_contents($cssPath)) : '';
        if ($css === '') {
            return '';
        }

        self::$injected = true;

        return "<style>\n{$css}\n</style>";
    }
}
