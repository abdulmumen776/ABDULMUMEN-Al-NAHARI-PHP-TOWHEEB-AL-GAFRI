<?php

namespace Componst;

require_once __DIR__ . '/ComponentStyles.php';

class SelectComponent
{
    public static function render(array $props = []): string
    {
        $defaults = [
            'name' => 'select_input',
            'label' => '',
            'options' => [],
            'placeholder' => 'اختر خياراً',
            'value' => null,
            'hint' => '',
            'id' => null,
            'required' => false,
        ];

        $config = array_merge($defaults, $props);

        $id = $config['id'] ?: 'select_' . substr(md5($config['name'] . microtime()), 0, 6);

        $labelHtml = $config['label'] !== ''
            ? sprintf(
                '<label class="component-input__label" for="%s">%s%s</label>',
                htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($config['label'], ENT_QUOTES, 'UTF-8'),
                $config['required'] ? ' <span class="component-input__required">*</span>' : ''
            )
            : '';

        $hintHtml = $config['hint'] !== ''
            ? sprintf(
                '<p class="component-input__hint" id="%s_hint">%s</p>',
                htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($config['hint'], ENT_QUOTES, 'UTF-8')
            )
            : '';

        $ariaDescribedBy = $hintHtml !== ''
            ? sprintf(' aria-describedby="%s_hint"', htmlspecialchars($id, ENT_QUOTES, 'UTF-8'))
            : '';

        $requiredAttr = $config['required'] ? ' required' : '';

        $optionsHtml = '';
        if ($config['placeholder'] !== '') {
            $selectedPlaceholder = $config['value'] === null ? ' selected' : '';
            $optionsHtml .= sprintf(
                '<option value="" disabled%s>%s</option>',
                $selectedPlaceholder,
                htmlspecialchars($config['placeholder'], ENT_QUOTES, 'UTF-8')
            );
        }

        foreach ($config['options'] as $key => $option) {
            $optionData = self::normalizeOption($key, $option);
            $selected = (string) $optionData['value'] === (string) $config['value'] ? ' selected' : '';
            $disabled = !empty($optionData['disabled']) ? ' disabled' : '';
            $optionsHtml .= sprintf(
                '<option value="%s"%s%s>%s</option>',
                htmlspecialchars($optionData['value'], ENT_QUOTES, 'UTF-8'),
                $selected,
                $disabled,
                htmlspecialchars($optionData['label'], ENT_QUOTES, 'UTF-8')
            );
        }

        $select = sprintf(
            '<select class="component-select" name="%s" id="%s"%s%s>%s</select>',
            htmlspecialchars($config['name'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
            $ariaDescribedBy,
            $requiredAttr,
            $optionsHtml
        );

        return ComponentStyles::inject() .
            '<div class="component-input__wrapper">' . $labelHtml . $select . $hintHtml . '</div>';
    }

    private static function normalizeOption(int|string $key, mixed $option): array
    {
        if (is_array($option)) {
            return [
                'label' => $option['label'] ?? (string) $key,
                'value' => $option['value'] ?? (string) $key,
                'disabled' => (bool) ($option['disabled'] ?? false),
            ];
        }

        return [
            'label' => (string) $option,
            'value' => is_string($key) ? $key : (string) $option,
            'disabled' => false,
        ];
    }
}
