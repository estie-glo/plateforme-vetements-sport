<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    protected $table = 'ligne_commande';
    public $timestamps = false;
    protected $guarded = [];
}
