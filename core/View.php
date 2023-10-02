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

    private function minifyHtml($code)
    {
        if(env('MINIFY_HTML') !== true) {
            return $code;
        }
        
        $search = array(
         
            // Remove whitespaces after tags
            '/\>[^\S ]+/s',
             
            // Remove whitespaces before tags
            '/[^\S ]+\</s',
             
            // Remove multiple whitespace sequences
            '/(\s)+/s',
             
            // Removes comments
            '/<!--(.|\s)*?-->/'
        );
        $replace = array('>', '<', '\\1');
        $code = preg_replace($search, $replace, $code);
        return $code;
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