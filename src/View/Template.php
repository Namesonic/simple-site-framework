<?php

namespace SimpleSite\View;

class Template {
    const DEFAULT_TEMPLATE = 'public';
    private View $view;

    /**
     * @param View $view
     */
    public function __construct(View $view) {
        $this->view = $view;
    }

    /**
     * @throws \Exception
     */
    public function render($templateName = self::DEFAULT_TEMPLATE): array|string|null
    {
        // Determine the template
        if ($this->view->getTemplate()) {
            $templateName = $this->view->getTemplate();
        }
        $filename = '../resources/layouts/' . $templateName . '.php';

        // Get current layout
        if (file_exists($filename)) {
            $output = file_get_contents($filename);
        } else {
            throw new \Exception('Missing site layout file for template "' . $templateName . '"');
        }

        // Place page into template body
        $output = preg_replace("/\{\{ body }}/", $this->view->getBody(), $output);

        // Replace additional variables
        return preg_replace("/\{\{ title }}/", $this->view->getTitle() ?: 'No Title', $output);
    }
}
