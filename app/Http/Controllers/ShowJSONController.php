<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;

class ShowJSONController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showJSON($id)
    {
        $file = Files::findOrFail($id);
        $filePath = storage_path("app/" . $file->path . $file->file);

        if (!file_exists($filePath)) {
            return redirect()->route('files.index')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        $jsonContent = file_get_contents($filePath);
        $jsonData = json_decode($jsonContent, true);

        return response()->json($jsonData, 200, [], JSON_PRETTY_PRINT);
    }
}