<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;

class ShowPDFController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showPDF($id)
    {
        $file = Files::findOrFail($id);
        $filePath = $file->path . DIRECTORY_SEPARATOR . $file->file;

        if (!file_exists($filePath)) {
            return redirect()->route('files.index')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->file . '"'
        ]);
    }


}
