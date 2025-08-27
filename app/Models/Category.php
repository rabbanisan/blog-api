<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'categories';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'slug',
  ];

  /**
   * Get the route key name for the model.
   *
   * @return string
   */
  public function getRouteKeyName()
  {
    return 'slug';
  }

  /**
   * Get the posts that belong to the category.
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function posts()
  {
    return $this->hasMany(Post::class, 'category_id');
  }
}
