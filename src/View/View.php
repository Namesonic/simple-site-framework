<?php

namespace SimpleSite\View;

class View {
    private string $page = '';
    private string $body = '';
    private string $title = '';
    private string $template = '';
    private array $params = [];
    private bool $addSuffix = false;

    public function __construct($page = '', array $vars = []) {
        $this->page = $page;
        $this->params = $vars;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getTitle(): string
    {
        $title = $this->title;

        if ($this->addSuffix) {
            if ($title) {
                $title .= " - " . config('SITE_NAME');
            }
        }

        if (!$title) {
            $title = config('SITE_NAME') . " - " . config('SITE_DESCRIPTION');
        }

        return $title;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setTitle($title, $addSuffix = false): static
    {
        $this->title = $title;
        $this->addSuffix = $addSuffix;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate($template): static
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function load(): void
    {
        if (!$this->page) {
            return;
        }

        $filename = '../resources/views/' . $this->page . '.php';
        if (file_exists($filename)) {
            if (!$this->body = file_get_contents($filename)) {
                throw new \Exception('Failed to load page template ' . $filename);
            }
        } else {
            throw new \Exception('Missing requested view template file: ' . $filename);
        }

    }

    public function replaceParams(): void
    {
        // Replace body variables
        $matches = [];
        preg_match_all('/\{\{\s+(.*?)\s+}}/m', $this->body, $matches);
        foreach ($matches[1] as $match) {
            $replace = '';
            if (isset($this->params[$match]) ) {
                $replace = $this->params[$match];
            }
            $this->body = str_replace("{{ $match }}", $replace, $this->body);
        }
    }
}