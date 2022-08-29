<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket';

    public $incrementing = true;

    protected $data = ['deleted_at'];

    protected $fillable = [
        'id', 'client_name', 'client_last_name', 'client_dui', 'description', 'solution', 'phone', 'sub_categorie_id', 'user_id', 'municipality_id', 'finished', 'created_at', 'updated_at', 'deleted_at',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function subCategorie()
    {
        return $this->belongsTo(SubCategorie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function format()
    {
        // dd(date("H:m", strtotime($this->created_at)));
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_last_name' => $this->client_last_name,
            'client_dui' => $this->client_dui,
            'description' => $this->description,
            'solution' => $this->solution,
            'phone' => $this->phone,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'municipality_name' => $this->municipality->municipality_name,
            'name_sub_cat' => $this->subCategorie?->name_sub_cat,
            'finished' => $this->finished,
            'finished_letters' => $this->finished ? 'Finalizado' : 'Pendiente',
            'date' => date("Y-m-d", strtotime($this->created_at)),
            'time' => date("H:m", strtotime($this->created_at)),
            'created_at' => date("d-m-Y", strtotime($this->created_at)),
        ];
    }

    public static function allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage)
    {
        $finished = $search == '%Finalizado%' ? true : false;

        return Ticket::select('ticket.*', 'sub_categorie.*', 'users.*', 'municipalities.*', 'ticket.id as id', 'ticket.created_at as created_at')
            ->join('sub_categorie', 'ticket.sub_categorie_id', '=', 'sub_categorie.id')
            ->join('users', 'ticket.user_id', '=', 'users.id')
            ->join('municipalities', 'ticket.municipality_id', '=', 'municipalities.id')

            ->where('ticket.client_name', 'like', $search)
            ->orWhere('ticket.id', 'like', $search)
            //->orWhere('u.name', 'like', $search)
            ->orWhere('ticket.client_dui', 'like', $search)
            ->orWhere('ticket.created_at', 'like', $search)
            ->orWhere('ticket.finished', $finished)

            ->skip($skip)
            ->take($itemsPerPage)
            ->orderBy("ticket.$sortBy", $sort)
            ->get()
            ->map(function ($ticket) {
                return $ticket->format();
            });
    }

    public static function counterPagination($search)
    {
        $finished = $search == '%Finalizado%' ? true : false;
        //dd($search, $finished);

        return Ticket::select('ticket.*', 'sub_categorie.*', 'users.*', 'municipalities.*', 'ticket.id as id', 'ticket.created_at as created_at')
            ->join('sub_categorie', 'ticket.sub_categorie_id', '=', 'sub_categorie.id')
            ->join('users', 'ticket.user_id', '=', 'users.id')
            ->join('municipalities', 'ticket.municipality_id', '=', 'municipalities.id')

            ->where('ticket.client_name', 'like', $search)
            ->orWhere('ticket.id', 'like', $search)
            //->orWhere('u.name', 'like', $search)
            ->orWhere('ticket.client_dui', 'like', $search)
            ->orWhere('ticket.created_at', 'like', $search)
            ->orWhere('ticket.finished', $finished)

            ->count();
    }
}
