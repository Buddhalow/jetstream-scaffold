<?php
namespace App\Http\Controllers;
use App\Models\Exposure;

use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ExposuresController extends Controller
{
    public function index()
    {
        return Inertia::render('Exposure/Index', [
            'exposures' => Exposure::all()
        ]);
    }
    public function store()
    {
        Exposure::create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['required', 'max:50'],
                'outlet' => ['required', 'max:50'],
                'reach' => ['required'],
                'type' => ['required'],
                'medium' => ['required']
            ])
        );

        return Redirect::route('exposures');
    }
}
