<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'utilisateur';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['mot_de_passe_hash'];

    public function getAuthPassword() { return $this->mot_de_passe_hash; }
    public function getRememberTokenName() { return ''; }
    public function estAdmin(): bool { return $this->role === 'administrateur'; }
}
