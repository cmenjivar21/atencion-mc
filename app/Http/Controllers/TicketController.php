<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\SubCategorie;
use App\Models\User;
use App\Models\Municipality;

use Illuminate\Http\Request;
use Encrypt;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $skip = ($request->page - 1) * $request->itemsPerPage;

        // Getting all the records
        if (($request->itemsPerPage == -1)) {
            $itemsPerPage =  Ticket::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $ticket = Ticket::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        // $ticket = Encrypt::encryptObject($ticket, "id");

        $total = Ticket::counterPagination($search);

        return response()->json([
            "status" => 200,
            "message" => "Registros obtenidos correctamente.",
            "records" => $ticket,
            "total" => $total,
            "success" => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = new Ticket;

        $ticket->client_name = $request->client_name;
        $ticket->client_last_name = $request->client_last_name;
        $ticket->client_dui = $request->client_dui;
        $ticket->description = $request->description;
        $ticket->solution = $request->solution;
        $ticket->phone = $request->phone;
        $ticket->sub_categorie_id = SubCategorie::where('name_sub_cat', $request->name_sub_cat)->first()->id;
        $ticket->user_id = $request->user()->id; //auth()->user()->id;  //User::where('name', $request->name)->first()->id; 
        $ticket->municipality_id = Municipality::where('municipality_name', $request->municipality_name)->first()->id;
        $ticket->finished = $request->finished ? true : false;
        $ticket->created_at = date(strtotime("$request->date $request->time"));

        $ticket->save();

        return response()->json([
            "status" => 200,
            "message" => "Registro creado correctamente.",
            "success" => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $ticket = Ticket::where('id', $request->id)->first();
        $ticket->client_name = $request->client_name;
        $ticket->client_last_name = $request->client_last_name;
        $ticket->client_dui = $request->client_dui;
        $ticket->description = $request->description;
        $ticket->solution = $request->solution;
        $ticket->phone = $request->phone;
        $ticket->sub_categorie_id = SubCategorie::where('id', $request->id)->first()->id;
        $ticket->user_id = $request->user()->id; //User::where('id', $request->id)->first()->id;  //User::where('name', $request->name)->first()->id; 
        $ticket->municipality_id = Municipality::where('id', $request->id)->first()->id;
        $ticket->finished = $request->finished;
        $ticket->deleted_at = $request->deleted_at;

        $ticket->save();

        return response()->json([
            "status" => 200,
            "message" => "Registro modificado correctamente.",
            "success" => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = isset($request->selected) ? $request->selected : [];

        if (count($data) > 0) {
            foreach ($data as $item) {
                $item = json_decode($item);

                Ticket::where('id', $id)->delete();
            }

            return response()->json([
                "status" => 200,
                "message" => "Registro eliminado correctamente.",
                "success" => true,
            ]);
        }

        Ticket::where('id', $request->id)->delete();

        return response()->json([
            "status" => 200,
            "message" => "Registro eliminado correctamente.",
            "success" => true,
        ]);
    }
}
