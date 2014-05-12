# Foreman

<p align="left">
<img height="300" src="https://s3-us-west-2.amazonaws.com/oss-avatars/foreman_round_readme.png">
</p>

Foreman is a Laravel scaffolding application that automates common tasks you typically perform with each new Laravel app you create.  The directives you want Forman to perform are outlined in a JSON based template file.

[![Build Status](https://travis-ci.org/Indatus/foreman.png?branch=master)](https://travis-ci.org/Indatus/foreman) [![Code Coverage](https://scrutinizer-ci.com/g/Indatus/foreman/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Indatus/foreman/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Indatus/foreman/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Indatus/foreman/?branch=master)

## README Contents

* [What does it do](#what-does-it-do)
* [Installation](#installation)
  * [Download the PHAR](#install-download)
  * [Compile from source](#install-compile)
  * [Install with Homebrew](#install-homebrew)
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


<a name="install-download" />
### Download the PHAR
The simplest method of installation is to simply [download the foreman.phar](https://github.com/indatus/foreman/raw/master/foreman.phar) file from this repository.

<a name="mv-easy-access" />
> **(Optional) Move and set permissions**
Now if you'd like you can move the PHAR to `/usr/local/bin` as `foreman` for easy access. You may need to grant the file execution privileges (`chmod +x`) before running commands.

<a name="install-compile" />
### Compile from source
To compile the foreman.phar file yourself, clone this repository and run the `box build` command. To run box commands, you must install [kherge/Box](https://github.com/kherge/Box).

[See optional move and permissions above](#mv-easy-access).

<a name="install-homebrew" />
### Install with Homebrew

You can also install Foreman via [Homebrew](http://brew.sh).  If you don't already have homebrew installed, you can install it with:

    ruby -e "$(curl -fsSL https://raw.github.com/Homebrew/homebrew/go/install)"

Next you'll need to add the sources necessary to install foreman:

    brew tap homebrew/dupes
    brew tap homebrew/versions
    brew tap josegonzalez/homebrew-php

Now update all formulae

    brew update

And then install Foreman

    brew install foreman

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
