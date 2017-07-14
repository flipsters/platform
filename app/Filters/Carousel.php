<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Carousel implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        if ($image->width() >= 300) {
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        if ($image->width() <= 250) {
            $image->resize(250, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->resizeCanvas(250, 150, 'center', false, array(255, 255, 255, 0));
        return $image;
    }
}
