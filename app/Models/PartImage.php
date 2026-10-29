<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartImage extends Model
{
    protected $table = 'part_images';

    protected $visible = ['image_path', 'is_primary'];
}
