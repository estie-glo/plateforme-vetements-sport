<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LignePanier extends Model
{
    protected $table = 'ligne_panier';
    public $timestamps = false;
    protected $guarded = [];

    public function variante() { return $this->belongsTo(Variante::class, 'variante_id'); }
}
