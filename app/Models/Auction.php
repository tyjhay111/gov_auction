<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'title',
        'description',
        'starting_price',
        'reserve_price',
        'current_price',
        'start_time',
        'end_time',
        'status',
        'created_by'
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'starting_price' => 'decimal:2',
            'reserve_price' => 'decimal:2',
            'current_price' => 'decimal:2',
        ];
    }

    public function images()
    {
        return $this->hasMany(AuctionImage::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }
}
