<?php

namespace Componst;

require_once __DIR__ . '/ComponentStyles.php';

class ThemeToggleComponent
{
    private const SCRIPT_KEY = 'component_theme_toggle_script';
    private static bool $scriptPrinted = false;

    public static function render(array $props = []): string
    {
        $defaults = [
            'id' => null,
            'initial' => 'light',
        ];

        $config = array_merge($defaults, $props);
        $id = $config['id'] ?: 'theme_toggle_' . substr(md5((string) microtime()), 0, 6);
        $initial = $config['initial'] === 'dark' ? 'dark' : 'light';

        $button = sprintf(
            '<button id="%s" class="component-theme-toggle" type="button" data-theme="%s">' .
            '<span class="component-theme-toggle__track">' .
            '<span class="component-theme-toggle__thumb"></span>' .
            '</span>' .
            '<span class="component-theme-toggle__label">الوضع <span class="component-theme-toggle__state">%s</span></span>' .
            '</button>',
            htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'),
            $initial === 'dark' ? 'الليلي' : 'النهاري'
        );

        return ComponentStyles::inject() . $button . self::script($id, $initial);
    }

    private static function script(string $id, string $initial): string
    {
        $dataset = $initial;
        $script = '';
        if (!self::$scriptPrinted) {
            self::$scriptPrinted = true;
            $script .= <<<HTML
<script>
document.documentElement.dataset.theme = document.documentElement.dataset.theme || '{$dataset}';
function __componstToggleTheme(id) {
    var btn = document.getElementById(id);
    if (!btn) return;
    var next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = next;
    btn.dataset.theme = next;
    var textSpan = btn.querySelector('.component-theme-toggle__state');
    if (textSpan) {
        textSpan.textContent = next === 'dark' ? 'الليلي' : 'النهاري';
    }
}
</script>
HTML;
        }

        $script .= <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('{$id}');
    if (!btn) return;
    btn.addEventListener('click', function () { __componstToggleTheme('{$id}'); });
});
</script>
HTML;

        return $script;
    }
}
