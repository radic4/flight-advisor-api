<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Search;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class City extends Model
{
    use HasFactory, Search, HasEagerLimit;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'country',
        'description',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
