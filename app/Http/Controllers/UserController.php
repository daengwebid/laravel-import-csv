<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ImportJob;

class UserController extends Controller
{
    public function index()
    {
        $user = User::paginate(10);
        return view('welcome', compact('user'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs(
                'public/import', $filename
            );

            ImportJob::dispatch($filename);
            return redirect()->back()->with(['success' => 'Upload success']);
        }  
        return redirect()->back()->with(['error' => 'Failed to upload file']);
    }
}
