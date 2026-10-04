<?php

namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = "products";
    protected $primaryKey = "pid";
    protected $allowedFields = [
        'name',
        'description',
        'quantity',
        'price',
        'status'
    ];
}