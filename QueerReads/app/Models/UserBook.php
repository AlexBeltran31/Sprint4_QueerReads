<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBook extends Pivot
{
    protected $table = 'user_books';
}
