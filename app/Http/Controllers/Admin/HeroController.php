<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeroController extends Controller
{
    public function index()
    {
        $heroContents = HeroContent::latest()->get();
        $activeHero = HeroContent::active()->first();
        
        return view('admin.hero.index', compact('heroContents', 'activeHero'));
    }

    public function edit($id)
    {
        $heroContent = HeroContent::findOrFail($id);
        return view('admin.hero.edit', compact('heroContent'));
    }

    public function update(Request $request, $id)
    {
        $heroContent = HeroContent::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'subtitle' => 'required|string|max:200',
            'description' => 'required|string|max:500',
            'cta_text' => 'required|string|max:50',
            'cta_link' => 'required|url',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $heroContent->update($request->all());

        return redirect()->route('admin.hero.index')
            ->with('success', 'Hero content berhasil diperbarui!');
    }
}