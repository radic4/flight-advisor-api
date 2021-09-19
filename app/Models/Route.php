<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'airline',
        'airline_id',
        'source_airport_id',
        'destination_airport_id',
        'codeshare',
        'stops',
        'equipment',
        'price',
        'synced',
    ];

    public function source()
    {
        return $this->belongsTo(Airport::class, 'source_airport_id');
    }

    public function destination()
    {
        return $this->belongsTo(Airport::class, 'destination_airport_id');
    }
}
