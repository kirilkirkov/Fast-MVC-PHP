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
            echo $renderContent;
        } else {
            echo str_replace(App::_LAYOUT_CONTENT_, $renderContent, $layoutContent);
        }
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