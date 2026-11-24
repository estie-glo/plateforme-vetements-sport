<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produit';
    public $timestamps = false;
    protected $guarded = [];

    public function categorie() { return $this->belongsTo(Categorie::class, 'categorie_id'); }
    public function variantes() { return $this->hasMany(Variante::class, 'produit_id'); }
}
