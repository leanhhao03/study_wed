<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteDocument extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $document = Document::findOrFail($request->document_id);

        $document->favorites()->attach(Auth::id());

        return response()->json(['message' => 'Document added to favorites'], 201);
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $document = Document::findOrFail($request->document_id);

        $document->favorites()->detach(Auth::id());

        return response()->json(['message' => 'Document removed from favorites'], 200);
    }

    public function index()
    {
        $documents = Auth::user()->favoriteDocuments;

        return response()->json($documents);
    }
}
