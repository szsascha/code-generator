# Code generator

Generate sourcecode from a unified JSON specification into different languages. The language templates are defined in twig. This project originates from a non-public development tool. It is not ready for public production use yet.

## Installation

1. Install composer and symfony cli
2. clone repository
3. composer install
4. symfony server:start

## Usage

The easiest way to run this project is to create source from the example JSON definition. Use the "example_input.json" from the main dir and send it with a POST request to following url:

http://localhost:8000/codegen/model/java

Now you get a java source as response.

It works the same way if you want a PHP source:

http://localhost:8000/codegen/model/php

(Please note that the url has changed)

The "model" in the url specifies the name of the template to use. You can create your own templates with twig. After you put them in the folder /templates/codegen you can call them. Please note that you have to create new macros for new languages. In particular, in lang.xxx.twig the language is described.