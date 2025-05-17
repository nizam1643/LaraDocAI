<?php

namespace App\Http\Controllers;

use App\Models\Pelajar;
use Illuminate\Http\Request;

class PelajarController extends Controller
{
    public function index() {
        return view('pelajar.index');
    }

    public function list() {
        return response()->json(Pelajar::all());
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'emel' => 'required|email|unique:pelajars',
            'program' => 'required',
        ]);
        return Pelajar::create($request->all());
    }

    public function show($id) {
        return Pelajar::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $pelajar = Pelajar::findOrFail($id);
        $pelajar->update($request->all());
        return $pelajar;
    }

    public function destroy($id) {
        Pelajar::destroy($id);
        return response()->json(['status' => 'deleted']);
    }
}
