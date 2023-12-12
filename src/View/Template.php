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
        if ($this->view->getLayout()) {
            $templateName = $this->view->getLayout();
        }
        $filename = '../resources/layouts/' . $templateName . '.php';

        // Get current layout
        if (file_exists($filename)) {
            $output = file_get_contents($filename);
        } else {
            throw new \Exception('Missing site layout file for template "' . $templateName . '"');
        }

        // Place page into template body
        $output = preg_replace("/\{\{ body }}/", str_replace('$', '\\$', $this->view->getBody()), $output);

        // Replace additional variables
        $output = preg_replace("/\{\{ title }}/", $this->view->getTitle(), $output);

        // Loop through remaining replacements in the template
        preg_match_all('/\{\{\s+(.*?)\s+}}/m', $output, $matches);
        $vars = $this->view->getParamsLayout();
        foreach ($matches[1] as $key) {
            // Substitute with any vars from the View template
            $output = preg_replace("/\{\{ $key }}/", $vars[$key] ?? '', $output);
        }

        return $output;
    }
}
