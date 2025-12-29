<?php

namespace Componst;

require_once __DIR__ . '/ComponentStyles.php';

class InputComponent
{
    public static function render(array $props = []): string
    {
        $defaults = [
            'name' => 'text_input',
            'label' => '',
            'value' => '',
            'placeholder' => 'ادخل البيانات هنا',
            'type' => 'text',
            'hint' => '',
            'id' => null,
            'required' => false,
        ];

        $config = array_merge($defaults, $props);

        $id = $config['id'] ?? null;
        if ($id === null || $id === '') {
            $id = 'input_' . substr(md5($config['name'] . microtime()), 0, 6);
        }

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

        $input = sprintf(
            '<input class="component-input" type="%s" name="%s" id="%s" value="%s" placeholder="%s"%s%s />',
            htmlspecialchars($config['type'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($config['name'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($id, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($config['value'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($config['placeholder'], ENT_QUOTES, 'UTF-8'),
            $ariaDescribedBy,
            $requiredAttr
        );

        return ComponentStyles::inject() .
            '<div class="component-input__wrapper">' . $labelHtml . $input . $hintHtml . '</div>';
    }
}
