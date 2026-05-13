<x-layouts.marketing :title="$post->meta_title ?? $post->title" :description="$post->meta_description ?? $post->excerpt">

<article>
    {{-- Hero --}}
    <section class="bg-[#0A2540] text-white py-16">
        <div class="max-w-3xl mx-auto px-4">
            @if(!empty($post->tags))
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($post->tags as $tag)
                <span class="text-xs bg-white/10 text-gray-300 px-2.5 py-1 rounded-full">{{ $tag }}</span>
                @endforeach
            </div>
            @endif
            <h1 class="font-['Fraunces'] text-4xl lg:text-5xl font-bold mb-6 leading-tight">{{ $post->title }}</h1>
            @if($post->excerpt)
            <p class="text-gray-300 text-lg leading-relaxed">{{ $post->excerpt }}</p>
            @endif
            <div class="flex items-center gap-4 mt-6 text-sm text-gray-400">
                <span>{{ $post->author->name }}</span>
                <span>|</span>
                @if($post->published_at)
                <span>{{ $post->published_at->format('j F Y') }}</span>
                <span>|</span>
                @endif
                <span>{{ $post->reading_time_minutes }} min read</span>
            </div>
        </div>
    </section>

    @if($post->featured_image_path)
    <div class="max-w-3xl mx-auto px-4 -mt-8">
        <img src="{{ Storage::url($post->featured_image_path) }}" alt="{{ $post->title }}" class="w-full rounded-2xl shadow-xl">
    </div>
    @endif

    {{-- Content --}}
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-4">
            <div class="prose prose-lg max-w-none prose-headings:font-['Fraunces'] prose-headings:text-[#0A2540] prose-a:text-[#E8663D]">
                {!! $post->content !!}
            </div>
        </div>
    </section>
</article>

{{-- Related posts --}}
@if($related->isNotEmpty())
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-['Fraunces'] text-2xl font-bold text-[#0A2540] mb-8">More from the blog</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $relPost)
            <a href="{{ route('blog.post', $relPost->slug) }}" class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
                <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-[#0A2540] transition leading-snug">{{ $relPost->title }}</h3>
                @if($relPost->excerpt)
                <p class="text-sm text-gray-600 line-clamp-2">{{ $relPost->excerpt }}</p>
                @endif
                <span class="block text-xs text-gray-400 mt-3">{{ $relPost->reading_time_minutes }} min read</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

</x-layouts.marketing>
