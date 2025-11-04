<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'address',
        'password',
        'email',
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
