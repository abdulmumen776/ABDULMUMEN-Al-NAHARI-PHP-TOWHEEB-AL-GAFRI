<?php

namespace Componst;

require_once __DIR__ . '/ComponentStyles.php';

class CardComponent
{
    public static function render(array $props = []): string
    {
        $defaults = [
            'title' => 'عنوان البطاقة',
            'content' => 'نص تجريبي يوضح كيفية استخدام بطاقة جميلة قابلة لإعادة الاستخدام داخل المشروع.',
            'meta' => [],
            'actions' => [],
        ];

        $config = array_merge($defaults, $props);

        $metaHtml = '';
        if (!empty($config['meta'])) {
            $metaHtml = '<div class="component-card__meta">';
            foreach ($config['meta'] as $item) {
                $metaHtml .= sprintf(
                    '<span>%s</span>',
                    htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8')
                );
            }
            $metaHtml .= '</div>';
        }

        $actionsHtml = '';
        if (!empty($config['actions'])) {
            $actionsHtml = '<div class="component-card__actions">';
            foreach ($config['actions'] as $action) {
                $label = htmlspecialchars((string) ($action['label'] ?? 'عرض المزيد'), ENT_QUOTES, 'UTF-8');
                $href = htmlspecialchars((string) ($action['href'] ?? '#'), ENT_QUOTES, 'UTF-8');
                $actionsHtml .= sprintf(
                    '<a class="component-card__btn" href="%s">%s</a>',
                    $href,
                    $label
                );
            }
            $actionsHtml .= '</div>';
        }

        $title = htmlspecialchars($config['title'], ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($config['content'], ENT_QUOTES, 'UTF-8');

        return ComponentStyles::inject() .
            '<article class="component-card">' .
            '<h3 class="component-card__title">' . $title . '</h3>' .
            $metaHtml .
            '<p class="component-card__content">' . $content . '</p>' .
            $actionsHtml .
            '</article>';
    }
}
