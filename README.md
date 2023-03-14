# Psalm Forbidden Plugin

![Integrate](https://github.com/aivchen/psalm-forbidden-plugin/workflows/Integrate/badge.svg)

### Installation

```
composer require --dev aivchen/psalm-forbidden-plugin
vendor/bin/psalm --init
vendor/bin/psalm-plugin enable aivchen/psalm-forbidden-plugin
```

### Features

- Forbids inheritance of any classes defined in the configuration (see below)

### Configuration

If you follow the installation instructions, the psalm-plugin command will add this plugin configuration to the `psalm.xml` configuration file.

```xml
<?xml version="1.0"?>
<psalm errorLevel="1">
    <!--  project configuration -->

    <plugins>
      <pluginClass class="Aivchen\PsalmForbiddenPlugin\Plugin" />
    </plugins>
</psalm>
```

To be able to forbid inheritance of some class add it to the config.
Example:

```xml
<pluginClass class="Psalm\SymfonyPsalmPlugin\Plugin">
    <extend>BadNamespace\BadClass</extend>
</pluginClass>
```
