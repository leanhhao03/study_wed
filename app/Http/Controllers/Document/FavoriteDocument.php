<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteDocument extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $exits = Favorite::where('user_id', Auth::id())
            ->where('document_id', $request->document_id)
            ->exists();

       if($exits){
           return response()->json(['message' => 'Document already in favorites'], 200);
       }

        Favorite::create
        ([
            'user_id' => Auth::id(),
            'document_id' => $request->document_id,
        ]);

        return response()->json(['message' => 'Document added to favorites'], 201);
    }
    public function destroy(Request $request)
    {
        $exits = Favorite::where('user_id', Auth::id())
            ->where('document_id', $request->document_id)
            ->exists();
        if($exits){
            Favorite::where('user_id', Auth::id())
                ->where('document_id', $request->document_id)
                ->delete();
        }
        return response()->json(['message' => 'Document removed from favorites'], 200);
    }

    public function index()
    {
        $documents = Document::whereIn('Dcm_id', function($query) {
            $query->select('document_id')
                  ->from('favorites')
                  ->where('user_id', Auth::id());
        })->get();
        return response()->json($documents);
    }
}
