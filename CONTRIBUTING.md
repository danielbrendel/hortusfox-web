# Contribution Guidelines

## Table of Contents
- [Overview](#overview)
- [Framework](#framework)
- [Localization](#localization)

## Overview

These guidelines provide information on the best practices on how to contribute to this repository.
Following this guidelines will fasten the review of pull requests.

The following rules for pull requests apply:

- Create an issue for your PR
- Only one PR for a specific issue
- Do not commit unrelated code to your PR
- Provide specific explanations about your PR
- If required, provide answers to possible questions
- Try not to put too much code into a single commit, separate as possible
- Follow the existing style of code

Contributions must comply with OpenSource definitions and need to be compatible with the projects license.
Also some principles of selfhosted must be respected. These include (but are not limited to) data privacy.

If you have any questions before submitting a PR, you can first create an issue to get answers.

## Framework

HortusFox is built with the [Asatru PHP Framework](https://github.com/danielbrendel/dnyAsatruPHP-App). The documentation is located in the `/doc` directory.
There is also an [online documentation](https://asatru-php.github.io/) available.

## Localization

Submitting new localizations helps to bring the project to a broader audience. Language files are located in the `/app/lang` directory.

Steps to create a new language

1. Create a new folder with your language and add the following files (copy them from `/app/en`). As an example we use `de` as german language.

```
/app/lang/de/app.php
/app/lang/de/errors.php
/app/lang/de/tb.php
```

2. Set the `_language_ident` token in your `app.php` file

```php
return [
    '_language_ident' => 'German',
    //...
];
```

3. Translate the original phrases into your desired language. Note that expressions in moustache-brackets are placeholders for variables.

```php
return [
    //...
    'some_expression' => 'Here comes the translated phrase',
    'another_expression' => 'The following expression is a variable placeholder: {var}.'
    //...
];
```

Users can now select the new language via their preferences and you can also set the language as default in the admin dashboard.