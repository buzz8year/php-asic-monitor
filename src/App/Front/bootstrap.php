<?php
/**
 * Main application bootstrap
 */

//$microtime = \microtime(true);

define("APP_DOCUMENT_ROOT", __DIR__ . '/../../../');
require_once APP_DOCUMENT_ROOT . 'src/autoload.php';
require_once APP_DOCUMENT_ROOT . 'src/config.php';

/* error handler */
\set_error_handler(array(new \App\ErrorsHandler(), "process"));

try {

    session_start();

    if (PHP_SAPI === "cli") {
        $path = isset($argv[1]) ? $argv[1] : "/";
    } else {
        $path = APP_URL_PREFIX . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "/");
        $path = parse_url($path, PHP_URL_PATH);
    }

    $dispatcher = new \App\Front\Dispatcher($path);

    if (!$dispatcher->dispatch()) {
        // Try to call another route
        foreach (glob(APP_DOCUMENT_ROOT . "/src/App/*/Routes.php") as $extended_routes) {
            $extended_routes_realpath = realpath($extended_routes);
            if ($extended_routes_realpath) {
                $ns_start = strpos($extended_routes_realpath, "App/");
                
                // print_r($ns_start);
                // Backslashes
                if (!$ns_start) {
                    try {
                        $ns_start = strpos($extended_routes_realpath, "App\\");
                    } catch (\Exception $e) {
                        
                    }
                }

                $ns_end = strpos($extended_routes_realpath, "Routes.php");
                $class_name = str_replace("/", "\\", substr($extended_routes_realpath, $ns_start - 1, -4));
                $dispatcher = new $class_name ($path);
                if ($dispatcher instanceof \App\Front\Dispatcher) {
                    /** @var \App\Front\Dispatcher $dispatcher */
                    if ($dispatcher->dispatch()) {
                        break;
                    }
                }
            }
        }

        if (!method_exists($dispatcher, "getControllerEntity") || !$dispatcher->getControllerEntity()) {
            throw new \App\HttpError4xxException(sprintf("Can't found route to %s", $path), 404);
        }
    }

    $dispatcher->getControllerEntity()->getLayout()
        ->setContent($dispatcher->getControllerEntity()->getView()->fetch())
        ->out();

} catch (\App\ApplicationException | \PDOException | \App\HttpError4xxException $e) {

    switch(get_class($e)) {
        case 'App\ApplicationException':
        case 'PDOException':
            $layout = new \App\Layouts\Html\ApplicationExceptionHtml();
            $response_code = 503;
            break;

        default:
            $layout = new \App\Layouts\Html\HttpExceptionHtml();
            $response_code = $e->getCode() ?: 404;
    }

    if ((int)$response_code === 403) {

        $view = new \App\Users\Views\Html\LoginUserView();
        $view->getResult()->addError($e->getMessage());

        $layout = new \App\Layouts\Html\DashboardLoginHtml();
        $layout
            ->setContent($view->fetch())
            ->out();
    } else {
        $layout
            ->setResponseCode($response_code)
            ->setMessage($e->getMessage())
            ->setCode($e->getCode())
            ->setFile($e->getFile())
            ->setLine($e->getLine())
            ->setTrace($e->getTraceAsString())
            ->out();
    }

}