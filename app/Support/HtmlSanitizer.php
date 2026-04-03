<?php

namespace App\Support;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><strike><h1><h2><h3><h4><h5><h6><ul><ol><li><a><blockquote><table><thead><tbody><tr><th><td><img><figure><figcaption><hr><span><div><sub><sup><oembed>';

    /**
     * Sanitize rich HTML from CKEditor before persistence.
     */
    public static function richHtml(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);

        if ($html === '') {
            return null;
        }

        $html = strip_tags($html, self::ALLOWED_TAGS);

        $html = preg_replace(
            '/<(script|style|iframe|object|embed|form|input|textarea|button|link|meta|base|svg|math)[^>]*>.*?<\/\1>/is',
            '',
            $html
        ) ?? $html;

        $html = preg_replace(
            '/<(script|style|iframe|object|embed|form|input|textarea|button|link|meta|base|svg|math)[^>]*\/?>/i',
            '',
            $html
        ) ?? $html;

        $html = preg_replace(
            '/\s+(on\w+|formaction|style|xmlns:x)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu',
            '',
            $html
        ) ?? $html;

        $html = preg_replace(
            '/\s(href|src|xlink:href)\s*=\s*("|\')\s*(javascript|vbscript|data:text)[^"\']*\2/iu',
            '',
            $html
        ) ?? $html;

        $html = preg_replace(
            '/\s(href|src)\s*=\s*("|\')\s*data:(?!image\/)[^"\']*\2/iu',
            '',
            $html
        ) ?? $html;

        return trim($html) === '' ? null : $html;
    }

    /**
     * Strip all HTML — for plain-text fields (review, description, titles in notifications).
     */
    public static function plainText(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        $text = preg_replace(
            '/<(script|style)[^>]*>.*?<\/\1>/is',
            '',
            $text
        ) ?? $text;

        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);

        return $text === '' ? null : $text;
    }
}
