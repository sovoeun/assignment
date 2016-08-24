<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
   	public function __construct()
    {
        $this->table = 'blogs';
    }
    
    protected $fillable = ['description', 'file'];

 
}
