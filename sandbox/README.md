# Sandbox

## What's inside?

Sandbox contains a minimal Laravel application with the package installed.  
You'll find:

- Example usage of the package components
- Demo pages, forms, and configuration
- Test migrations, seeders, and Filament integration

## What is it for?

Sandbox is used for local development and testing of the package.  
It allows you to:

- Visually verify that new functionality works correctly
- Manually test components during development or before submitting a pull request
- Debug potential issues in a real Laravel + Filament application

## 🧩 How the package is connected in the sandbox

The package is loaded into the `sandbox` Laravel application via a local symlink using
the following configuration in the sandbox’s `composer.json`:

```json
{
    "repositories": {
        "local": {
            "type": "path",
            "url": "../",
            "options": {
                "symlink": true
            }
        }
    },
    "require": {
        "kpebedko22/filament-yandex-map": "@dev"
    }
}
```

This means that the package from the parent directory (`../`) is symlinked into
the sandbox during development, so changes made to the package code are immediately
reflected in the sandbox.

Use `php artisan filament:assets` to publish assets (js files for form and infolist components).

## 📦 How the sandbox and dev files are excluded from distribution

In the package's own `composer.json`, the following options are set to exclude unnecessary
development files when publishing the package (e.g. to Packagist):

```json
{
    "archive": {
        "exclude": [
            "/sandbox",
            "docker-compose.yml",
            "/deploy",
            "Makefile"
        ]
    },
    "exclude-from-classmap": [
        "sandbox/"
    ]
}
```

This ensures that files and folders like `./sandbox`, Docker configs, deployment scripts,
and Makefile **are not included** when the package is installed in a real project via Composer.

As a result, end users will get only the production-ready code — no dev dependencies or
sandbox artifacts.
