<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commande';
    public $timestamps = false;
    protected $guarded = [];

    public const LIBELLES = [
        'en_attente' => 'En attente de paiement',
        'payee' => 'Payée',
        'en_preparation' => 'En préparation',
        'expediee' => 'Expédiée',
        'livree' => 'Livrée',
    ];

    public function getLibelleAttribute() { return self::LIBELLES[$this->statut] ?? $this->statut; }

    public function lignes() { return $this->hasMany(LigneCommande::class, 'commande_id'); }
    public function client() { return $this->belongsTo(User::class, 'utilisateur_id'); }
    public function adresse() { return $this->belongsTo(Adresse::class, 'adresse_id'); }
}
