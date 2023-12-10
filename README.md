# Simple Site - Scaffolding

Use this to scaffold a simple html template project using a lightweight MVC structure.

## Features

- Lightweight MVC Structure (sans M)
- Multiple Layouts
- No CSS/js dependency
- Simple variable replacement handling for templates
- Configuration Handling
- Exception Handling

## Requires

- PHP 8.1 or greater

## Installation

Exchange `my-site-name` below with the folder name that will contain your new website.

`composer create-project namesonic/simple-site my-site-name`

## Configuration

Your webserver should be configured to point the document root to the `my-site-name/public` folder.

## Development

```
app\
    Http\
        Controllers\
            Add new pages to the website here
        Exceptions\
            Add custom code exception handlers here
config\
    Store configuration files here
public\
    This is the public facing folder for the application
    js\
    css\
    img\
resources\
    errordocs\
        Website errordocuments are served from this folder <404.php>
    layouts\
        Global template layouts are stored here <layoutname.php>
    views\
        Individual page layouts are stored here <pagename.php>
```
