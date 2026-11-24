<?php

namespace Core;

/**
 * Collapse whitespace and remove html comments.
 * Content of <pre>, <textarea>, <script> and <style> is kept untouched.
 */
class HtmlMinifier
{
    public static function minify(string $html): string
    {
        // replace protected blocks with placeholders and put them back after minify
        $preserved = [];
        $protectedHtml = preg_replace_callback('/<(pre|textarea|script|style)\b.*?<\/\1>/is', static function (array $match) use (&$preserved): string {
            $placeholder = "\x1A" . count($preserved) . "\x1A";
            $preserved[$placeholder] = $match[0];

            return $placeholder;
        }, $html);

        $minified = $protectedHtml === null ? null : preg_replace(['/<!--.*?-->/s', '/\s+/'], ['', ' '], $protectedHtml);
        if($minified === null) {
            return $html;
        }

        return strtr($minified, $preserved);
    }
}
