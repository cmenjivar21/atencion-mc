<?php

namespace App\Http\Controllers;

use App\Models\SubCategorie;
use App\Models\Categorie;

use Illuminate\Http\Request;
use Encrypt;

class SubCategorieController extends Controller
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
            $itemsPerPage =  SubCategorie::count();
            $skip = 0;
        }

        $sortBy = (isset($request->sortBy[0])) ? $request->sortBy[0] : 'id';
        $sort = (isset($request->sortDesc[0])) ? "asc" : 'desc';

        $search = (isset($request->search)) ? "%$request->search%" : '%%';

        $subcategorie = SubCategorie::allDataSearched($search, $sortBy, $sort, $skip, $itemsPerPage);
        $subcategorie = Encrypt::encryptObject($subcategorie, "id");

        $total = SubCategorie::counterPagination($search);

        return response()->json([
            "status" => 200,
            "message"=>"Registros obtenidos correctamente.",
            "records" => $subcategorie,
            "total" => $total,
            "success"=>true,
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
        $subcategorie = new SubCategorie;

		$subcategorie->name_sub_cat = $request->name_sub_cat;
		$subcategorie->categorie_id = Categorie::where('name_cat', $request->name_cat)->first()->id;
		$subcategorie->deleted_at = $request->deleted_at;

        $subcategorie->save();

        return response()->json([
            "status"=>200,
            "message"=>"Registro creado correctamente.",
            "success"=>true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategorie  subcategorie
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategorie $subcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = Encrypt::decryptArray($request->all(), 'id');

        $subcategorie = SubCategorie::where('id', $data['id'])->first();
		$subcategorie->name_sub_cat = $request->name_sub_cat;
		$subcategorie->categorie_id = Categorie::where('name_cat', $request->name_cat)->first()->id;
		$subcategorie->deleted_at = $request->deleted_at;

        $subcategorie->save();

        return response()->json([
            "status"=>200,
            "message"=>"Registro modificado correctamente.",
            "success"=>true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = isset($request->selected) ? $request->selected : [];

        if (count($data) > 0) {
            foreach ($data as $item) {
                $item = json_decode($item);

                SubCategorie::where('id', $id)->delete();
            }

            return response()->json([
                "status"=>200,
                "message"=>"Registro eliminado correctamente.",
                "success"=>true,
            ]);
        } 

        $id = Encrypt::decryptValue($request->id);

        SubCategorie::where('id', $id)->delete();

        return response()->json([
            "status"=>200,
            "message"=>"Registro eliminado correctamente.",
            "success"=>true,
        ]);
    }
}
