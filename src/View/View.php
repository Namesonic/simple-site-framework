<?php

namespace SimpleSite\View;

class View {
    private string $page = '';
    private string $body = '';
    private string $title = '';
    private array $params = [];

    public function __construct($page, array $vars = []) {
        $this->page = $page;
        $this->params = $vars;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setTitle($title, $addSuffix = false): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function load(): void
    {
        $filename = '../resources/views/' . $this->page . '.php';
        if (file_exists($filename)) {
            if (!$this->body = file_get_contents($filename)) {
                throw new \Exception('Failed to load page template ' . $filename);
            }
        } else {
            throw new \Exception('Missing requested view template file: ' . $filename);
        }

        $this->replaceParams();
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