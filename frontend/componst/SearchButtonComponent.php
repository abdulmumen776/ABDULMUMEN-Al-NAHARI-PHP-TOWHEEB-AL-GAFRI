<?php

namespace Componst;

require_once __DIR__ . '/ComponentStyles.php';

class SearchButtonComponent
{
    public static function render(array $props = []): string
    {
        $defaults = [
            'label' => 'بحث',
            'type' => 'submit',
            'loading' => false,
            'fullWidth' => false,
        ];

        $config = array_merge($defaults, $props);

        $classes = 'component-search-button';
        if (!empty($config['fullWidth'])) {
            $classes .= ' component-search-button--full';
        }

        $icon = self::iconSvg();
        if (!empty($config['loading'])) {
            $icon = self::spinner();
        }

        $label = htmlspecialchars($config['label'], ENT_QUOTES, 'UTF-8');
        $type = htmlspecialchars($config['type'], ENT_QUOTES, 'UTF-8');

        $button = sprintf(
            '<button class="%s" type="%s">%s<span class="component-search-button__label">%s</span></button>',
            $classes,
            $type,
            $icon,
            $label
        );

        return ComponentStyles::inject() . $button;
    }

    private static function iconSvg(): string
    {
        return <<<SVG
<span class="component-search-button__icon" aria-hidden="true">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
        <path d="M21 21L16.65 16.65M18 11C18 14.866 14.866 18 11 18C7.13401 18 4 14.866 4 11C4 7.13401 7.13401 4 11 4C14.866 4 18 7.13401 18 11Z"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</span>
SVG;
    }

    private static function spinner(): string
    {
        return <<<SVG
<span class="component-search-button__spinner" aria-hidden="true">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity="0.25" stroke-width="3"/>
        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
    </svg>
</span>
SVG;
    }
}
