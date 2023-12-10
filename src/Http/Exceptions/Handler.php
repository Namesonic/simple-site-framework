<?php

namespace SimpleSite\Http\Exceptions;

class Handler {
    public function handle(\Exception $e): void
    {
        print "<b>Uncaught Exception</b> > " . $e->getMessage() . "<br>";
        print "<blockquote>";
        print "<b>File:</b> " . $e->getFile() . " on line #{$e->getLine()}<br>";
        print "<p><b>Trace:</b>";
        print "<pre>{$e->getTraceAsString()}</pre>";
        print "</p>";
        print "</blockquote>";
    }
}