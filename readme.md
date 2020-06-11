# Simple wrapper for handling GDAL

This package provides simple way to interact with GDAL library.

## Instalation

Run following composer command.

```
composer require geodeticca/spatial
```
## Example

```
$warp = new Warp($inputPath, $outputPath);
$warp
    ->addParam('-overwrite')
    ->addParam('-of GTiff')
    ->addParam('-s_srs EPSG:3857')
    ->addParam('-t_srs EPSG:4326');
$warp->execute();
```
