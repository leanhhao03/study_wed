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
            'document_id' => 'required|exists:documents,Dcm_id',
        ]);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $userId = Auth::id();
        $exists = Favorite::where('user_id', $userId)
            ->where('document_id', $request->document_id)
            ->exists();

       if($exists){
           return response()->json(['message' => 'Document already in favorites'], 200);
       }
        Favorite::create
        ([
            'user_id' => $userId,
            'document_id' => $request->document_id,
        ]);

        return response()->json(['message' => 'Document added to favorites'], 201);
    }
    public function destroy(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deleted = Favorite::where('user_id', Auth::id())
            ->where('document_id', $request->document_id)
            ->delete();
        if ($deleted) {
                return response()->json(['message' => 'Document removed from favorites'], 200);
            }
        return response()->json(['message' => 'Document removed from favorites'], 200);
    }

    public function index()
    {
        $userId = Auth::id();

        $documents = Document::whereIn('Dcm_id', function($query) use ($userId) {
            $query->select('document_id')
                  ->from('favorites')
                  ->where('user_id', $userId);
        })->get();
        return response()->json($documents);
    }

}
