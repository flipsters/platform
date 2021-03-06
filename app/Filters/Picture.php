<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Picture implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        if ($image->width() >= 1500) {
            $image->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        // insert watermark
        $image->insert('https://cdn.jsdelivr.net/npm/swapdelivr@1.0.0/img/watermark.png'), 'bottom-right', 10, 10);

        return $image;
    }
}
