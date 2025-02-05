# phpunuhi export to excel

![Build Status](https://github.com/tumtum/phpunuhi-export-excel/actions/workflows/ci_pipe.yml/badge.svg)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/tumtum/phpunuhi-export-excel)
![GitHub commits since latest release (by date)](https://img.shields.io/github/commits-since/tumtum/phpunuhi-export-excel/latest)
![Packagist Downloads](https://img.shields.io/packagist/dt/tumtum/phpunuhi-export-excel?color=green&logo=packagist)


Diese Erweiterung für das Projekt [phpunuhi](https://github.com/boxblinkracer/phpunuhi) ermöglicht den Export von Übersetzungen in eine 
Excel-Tabelle. Mit dieser Funktion können alle Übersetzungsdaten bequem in einer Excel-Datei 
gespeichert und verwaltet werden. Ideal, um Übersetzungen übersichtlich zu exportieren und 
weiterzuverarbeiten.

## Anleitung zur Benutzung

## Installation

Die Extension wird automatisch bei phpunuhi registriert und steht anschließend unmittelbar zur Verfügung.

```shell
composer require tumtum/phpunuhi-export-excel
```

### Sprachen expotieren und in eine Excel-Datei speichern

Mit diesem Befehl wird eine Excel-Datei erstellt, 
die nach dem Muster `TranslationProject_[HEUTIGES DATUM YYMMTT].xlsx` heißt.

Jedem Übersetzungsset entspricht ein eigenes Blatt.

```shell
./bin/phpunuhi export --format=excel
```

#### Optional: Bestimmte Übersetzungssets ignorieren

Falls du einige Übersetzungssets ausschließen möchtest, kannst du die Option `--excel-skip-sets` verwenden. 
Dabei kannst du eine Liste von Sets angeben, getrennt durch ein Komma, oder ein Muster nutzen (mit %), 
um alle Sets auszuschließen, die dem Muster entsprechen.

```shell
./bin/phpunuhi export --format=excel --excel-skip-sets=storefront,%administration%,Swag%
```

In diesem Beispiel wird der Set `storefront`, alle Sets, die `administration` enthalten und 
mit `Swag` beginnen, ignoriert.

### Sprachen importieren aus einer Excel-Datei

Jedes Übersetzungsset muss separat importiert werden, wobei jedes Übersetzungsset in der Excel-Datei 
ein eigenes Blatt ist.

```shell
./bin/phpunuhi import --format=excel --file=import/TranslationProject_YYMMDD.xlsx --set=storefront
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
