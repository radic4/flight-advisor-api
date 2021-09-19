<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'city_id',
        'iata',
        'icao',
        'lat',
        'long',
        'altitude',
        'timezone',
        'dst',
        'tz',
        'type',
        'source',
        'synced',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
