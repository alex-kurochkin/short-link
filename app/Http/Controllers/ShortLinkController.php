<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function redirect($code)
    {
        $link = Link::where('code', $code)->firstOrFail();

        // Записываем клик
        Click::create([
            'link_id' => $link->id,
            'ip_address' => request()->ip(),
            'clicked_at' => now(),
        ]);

        return redirect($link->original_url);
    }

    public function create(Request $request, CodeGenerator $generator)
    {
        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        try {
            $code = $generator->generate();
        } catch (\RuntimeException $e) {
            return back()->withErrors([
                'original_url' => $e->getMessage(),
            ]);
        }

        $link = Link::create([
            'user_id' => auth()->id(),
            'code' => $code,
            'original_url' => $validated['original_url'],
        ]);

        return back()->with('success', 'Ссылка создана: ' . $link->short_url);
    }
}
