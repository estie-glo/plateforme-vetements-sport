<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variante extends Model
{
    protected $table = 'variante';
    public $timestamps = false;
    protected $guarded = [];

    public function produit() { return $this->belongsTo(Produit::class, 'produit_id'); }

    // Prix spécifique de la variante, sinon prix du produit
    public function getPrixFinalAttribute()
    {
        return $this->prix_specifique ?? $this->produit->prix;
    }
}
