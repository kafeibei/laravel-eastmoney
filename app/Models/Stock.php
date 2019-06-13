<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class Stock extends Model
{
  protected $table = 'stock';
  public $timestamps = false;

  public function __construct()
  {
    self::copy();
  }

  public static function copy()
  {
    if(!Schema::hasTable('stock')){
      Schema::create('stock', function (Blueprint $table) {
        $table->increments('id');
        $table->string('stock_id');
        $table->text('company');
      });
    }
  }
}
