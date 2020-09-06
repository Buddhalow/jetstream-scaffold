<?php
namespace App\Http\Controllers;
use App\Models\Coverage;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CoveragesController extends Controller
{
    public function index()
    {
        return Inertia::render('Coverage/Index', [
            'coverages' => Coverage::all()
        ]);
    }
    public function store()
    {
        $result = Coverage::create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['required', 'max:50'],
                'outlet' => ['required', 'max:50'],
                'reach' => ['required'],
                'type' => ['required'],
                'medium' => ['required'],
                'covered' => ['required']
            ])
        );
        return Redirect::route('coverages');
    }
    public function delete() {
        Coverage::delete();
        return Redirect::route('coverages');
    }
}
