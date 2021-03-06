<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Square implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        if ($image->width() >= 300) {
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $image->resizeCanvas(200, 200, 'center', false, array(255, 255, 255, 0));
        return $image;
    }
}
