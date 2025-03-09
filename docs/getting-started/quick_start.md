# Quick Start

## Technical requirements
- PHP 8.2 or higher
- PHP extensions: `json`, `mbstring`, `xml`
- [composer](https://getcomposer.org/)

If you plan to use Umbrella on a new project, [create a new Symfony app first](https://symfony.com/doc/current/setup.html#creating-symfony-applications) using this command : 

```bash
composer create-project symfony/skeleton:"7.2.x" my_project_directory
cd my_project_directory
composer require webapp
```

## Installation
```bash
composer require umbrella2/admin-bundle

# For last development version
# composer require umbrella2/admin-bundle:"dev-master"
```