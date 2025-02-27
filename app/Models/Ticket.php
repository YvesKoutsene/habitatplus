<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    use HasFactory;

    protected $fillable = ['titre', 'description', 'piece_jointe', 'statut', 'id_user', 'id_categorie'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieTicket::class, 'id_categorie');
    }

    public function messages()
    {
        return $this->hasMany(MessageTicket::class);
    }

}
