Example:

```php
<?php

use Rikudou\Sims4\Paintings\PaintingPackage;
use Rikudou\Sims4\Paintings\Enums\PaintingStyle;
use Rikudou\Sims4\Paintings\Enums\CanvasType;

$package = new PaintingPackage('AuthorName', 'PackageName');

$classicPaintingsPack = $package->createPack(PaintingStyle::CLASSIC);
$image1 = $classicPaintingsPack->createImage(
    '/path/to/image.jpg',
    'ImageName',
    CanvasType::CANVAS_SIZE_LARGE
);

$package->write('/path/to/result.package');
```

Example with resizer:

```php
<?php

use Rikudou\Sims4\Paintings\Helper\ImageResizer;
use Rikudou\Sims4\Paintings\PaintingPackage;
use Rikudou\Sims4\Paintings\Enums\CanvasType;
use Rikudou\Sims4\Paintings\Enums\PaintingStyle;

$imagePath = '/path/to/image.jpg';
$resizedImagePath = '/path/to/new-image.jpg';
$resizer = new ImageResizer($imagePath, ImageResizer::SIZE_LARGE);
$resizer->write($resizedImagePath);

$package = new PaintingPackage('AuthorName', 'PackageName');
// shorthand for creating images directly, pack will be created automatically
$image = $package->createImage(
    $resizedImagePath,
    'ImageName',
    CanvasType::CANVAS_SIZE_LARGE,
    PaintingStyle::SURREALISM
);

$package->write('/path/to/result.package');
```
