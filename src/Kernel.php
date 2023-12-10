<?php
namespace SimpleSite;

use SimpleSite\View\Template;
use SimpleSite\View\View;

class Kernel {

    private string $namespace;

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
            $page = $_REQUEST['p'];
        } else {
            $page = 'home';
        }

        if (preg_match('/^\w+$/', $page)) {
            $controllerName = $this->namespace . '\\Controllers\\' . ucfirst($page) . "Controller";

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
        } else {
            header("HTTP/1.0 400 Bad Request");
            print file_get_contents('../resources/errordocs/400.php');
        }
    }
}