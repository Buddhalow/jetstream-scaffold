<?php
class MoodsController extends Controller
{
    public function index()
    {
        return Inertia::render('Moods/Index', [
            'moods' => Mood::all(),
            'create_url' => URL::route('moods.create'),
        ]);
    }
}
