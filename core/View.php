<?php

namespace Core;

/**
 * Open resources/views
 */
class View
{
    private $responseStatus = 200;
    private static string $layout = '';

    /**
     * Render view
     */
    public function render(string $view, array $data = [])
    {
        extract($data);
        http_response_code($this->responseStatus);

        $layoutContent = null;
        if(self::$layout != null) {
            ob_start();
            include self::$layout;
            $layoutContent = ob_get_clean();
        }

        ob_start();
        include App::VIEWS_DIR . "$view.php";
        $renderContent = ob_get_clean();
        if($layoutContent === null) {
            echo $this->minifyHtml($renderContent);
        } else {
            echo $this->minifyHtml(str_replace(App::_LAYOUT_CONTENT_, $renderContent, $layoutContent));
        }
    }

    /**
     * Collapse whitespace and remove html comments.
     * Content of <pre>, <textarea>, <script> and <style> is kept untouched.
     */
    private function minifyHtml(string $html): string
    {
        if(env('MINIFY_HTML') !== true) {
            return $html;
        }

        // replace protected blocks with placeholders and put them back after minify
        $preserved = [];
        $protectedHtml = preg_replace_callback('/<(pre|textarea|script|style)\b.*?<\/\1>/is', function (array $match) use (&$preserved): string {
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

    /**
     * Load file in view
     */
    public function view(string $view, array $data = [])
    {
        extract($data);
        include App::VIEWS_DIR . "$view.php";
    }

    public function setResponseStatus(int $status)
    {
        $this->responseStatus = $status;
        return $this;
    }

    /**
     * Set layout of the app
     */
    public static function layout(string $view)
    {
        self::$layout = App::VIEWS_DIR . "$view.php";
    }
}