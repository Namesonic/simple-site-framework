<?php
namespace SimpleSite;

use SimpleSite\View\Template;
use SimpleSite\View\View;

class Kernel {

    private string $namespace;

    // Allow sub folders and letters and numbers only in a url path
    // must start with a letter
    // must end with a letter or number
    const REGEX_ALLOWED_URL_FORMAT = '!^[a-z][a-z0-9]+$!';

    // This is the default controller that is loaded if no page is specified
    const DEFAULT_CONTROLLER_NAME = "index";

    public function __construct($namespace)
    {
        !$namespace && die('Id like to know your namespace please');
        $this->namespace = $namespace;

        require (__DIR__ . '/bootstrap/index.php');
    }

    public function handle(): void
    {
        // Load the current page/controller
        if (isset($_REQUEST['p'])) {
            $page = rtrim($_REQUEST['p'], '/\\');
        } else {
            $page = self::DEFAULT_CONTROLLER_NAME;
        }

        // Build the controller path
        $controllerName = $this->namespace . '\\Controllers';
        foreach(explode('\\', $page) as $piece) {
            // Check the URL path piece meets our allowed formats (a-z0-9...)
            if (preg_match(self::REGEX_ALLOWED_URL_FORMAT, $piece)) {
                $controllerName .= "\\" . ucfirst($piece);
            } else {
                header("HTTP/1.0 400 Bad Request");
                print file_get_contents('../resources/errordocs/400.php');
                exit;
            }
        }
        $controllerName .= "Controller";

        // Look for matching class?
        if (class_exists("$controllerName")) {
            $pageController = new $controllerName;

            // Run the controller handle method
            /** @var View $view */
            $view = $pageController->handle();

            // Load the prepared view / replace params
            $view->load();
            $view->replaceParams();

            // Create a template with the prepared view
            $template = new Template($view);

            // Output the template
            echo $template->render();
        } else {
            header("HTTP/1.0 404 Not Found");
            print file_get_contents('../resources/errordocs/404.php');
            // print "Requested page not found (Missing Controller $controllerName)";
        }
    }
}