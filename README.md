# Foreman

<p align="left">
<img height="300" src="https://s3-us-west-2.amazonaws.com/oss-avatars/foreman_round_readme.png">
</p>

Foreman is a Laravel scaffolding application that automates common tasks you typically perform with each new Laravel app you create.  The directives you want Forman to perform are outlined in a JSON based template file.

## README Contents

* [What does it do](#what-does-it-do)
* [Installation](#installation)
  * [Updating Foreman](#updating)
* [Scaffolding a Template](#scaffolding)
  * [Working with a template](#working-with-template)
* [Build a Laravel app from a template](#building)


<a name="what-does-it-do" />
## What does it do?

* Structure
  * Copy files and directories 
  * Move files and directories 
  * Delete files and directores
  * Touch files
  * Make new directories
* Composer
  * Require composer package dependencies
  * Require development composer package dependencies
  * Manage the composer autoload > classmap
  * Manage the composer autoload > psr-0 settings
  * Manage the composer autoload > psr-4 settings

<a name="installation" />
## Installation

The simplest method of installation is to simply [download the foreman.phar](https://github.com/indatus/foreman/raw/master/foreman.phar) file from this repository.

To compile the foreman.phar file yourself, clone this repository and run the `box build` command. To run box commands, you must install [kherge/Box](https://github.com/kherge/Box).

Once the Phar has been compiled, move it to `/usr/local/bin` as `foreman` for easy access. You may need to grant the file execution privileges (`chmod +x`) before running commands.

<a name="updating" />
### Updating Foreman

To update Foreman, you may use the `foreman self-update` command.

<a name="scaffolding" />
## Scaffolding a Template

To get started you'll want to generate a default / blank Foreman template.  You can do this with the command:

    foreman scaffold /path/to/my/template.json

> Note: If you provide a directory instead of a file, Foreman will create a **foreman-tpl.json** file in the given directory.

A default Foreman template looks like this:

```json
{
    "structure": {
        "copy": [
            {
                "from": "",
                "to": ""
            }
        ],
        "move": [
            {
                "from": "",
                "to": ""
            }
        ],
        "delete": [

        ],
        "touch": [

        ],
        "mkdir": [

        ]
    },
    "composer": {
        "require": [
            {
                "package": "laravel/framework",
                "version": "4.1.*"
            }
        ],
        "require-dev": [
            {
                "package": "",
                "version": ""
            }
        ],
        "autoload": {
            "classmap": [
                "app/commands",
                "app/controllers",
                "app/models",
                "app/database/migrations",
                "app/database/seeds",
                "app/test/TestCase.php"
            ],
            "psr-0": [

            ],
            "psr-4": [

            ]
        }
    }
}
```

<a name="working-with-template" />
### Working with a template

For structure references you can use either an absolute path (to a file or directory) or a path relative to your new application's root.

<a name="building" />
## Build a Laravel app from a template

In order to use your Foreman template to generate a new Laravel application you just need to use the `build` command.  

The first argument is the absolute path to where you want to install the application, the second argument is the location of the template file.

    foreman build /install/app/here /path/to/template.json

Now Foreman will generate your Laravel app with all your configuration applied.
