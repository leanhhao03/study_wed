<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Auth::user()->notes;
        return response()->json($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);


        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => 1, // Gán user_id
        ]);

        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $note = Note::find($id);

        if(!$note){
            return response()->json(['message' => 'Note not found'], 404);
        }
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $note = Note::where('user_id', Auth::id())->where('id', $id)->first(); 

        if (!$note) {
            return response()->json(['message' => 'Note not found or unauthorized'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($note);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {       
            $note = Note::where('user_id', Auth::id())->where('id', $id)->first(); 
    
            if (!$note) {
                return response()->json(['message' => 'Note not found or unauthorized'], 404);
            }
    
            $note->delete();
            return response()->json(['message' => 'Deleted successfully']);
    }
}
