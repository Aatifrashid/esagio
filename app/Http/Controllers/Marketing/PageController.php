<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('marketing.home');
    }

    public function features(): View
    {
        return view('marketing.features');
    }

    public function pricing(): View
    {
        return view('marketing.pricing');
    }

    public function vsBrightplans(): View
    {
        return view('marketing.vs-brightplans');
    }

    public function about(): View
    {
        return view('marketing.about');
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        \Illuminate\Support\Facades\Mail::raw(
            "Name: {$validated['name']}\nEmail: {$validated['email']}\n\n{$validated['message']}",
            fn ($m) => $m->to('hello@esagio.com')->subject('Contact form: ' . $validated['name'])->replyTo($validated['email'])
        );

        return back()->with('success', 'Message sent. We will reply within one business day.');
    }

    public function privacy(): View
    {
        return view('marketing.privacy');
    }

    public function terms(): View
    {
        return view('marketing.terms');
    }

    public function blog(Request $request): View
    {
        $posts = BlogPost::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(9);

        return view('marketing.blog', compact('posts'));
    }

    public function blogPost(string $slug): View
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('marketing.blog-post', compact('post', 'related'));
    }
}
