<x-layouts.marketing :title="'Blog'">

<section class="bg-[#0A2540] text-white py-16">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="font-['Fraunces'] text-4xl font-bold mb-3">The Esagio Blog</h1>
        <p class="text-gray-300">Clinical tips, product updates, and real talk about running a dental clinic.</p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($posts->isEmpty())
        <div class="text-center py-20 text-gray-500">
            <p class="text-lg">No posts yet. Check back soon.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <a href="{{ route('blog.post', $post->slug) }}" class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition group">
                @if($post->featured_image_path)
                <img src="{{ Storage::url($post->featured_image_path) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gradient-to-br from-[#0A2540]/10 to-[#E8663D]/10"></div>
                @endif
                <div class="p-6">
                    @if(!empty($post->tags))
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach(array_slice($post->tags, 0, 2) as $tag)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    <h2 class="font-semibold text-gray-900 mb-2 group-hover:text-[#0A2540] transition leading-snug">{{ $post->title }}</h2>
                    @if($post->excerpt)
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                    @endif
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>{{ $post->author->name }}</span>
                        <span>{{ $post->reading_time_minutes }} min read</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @endif

    </div>
</section>

</x-layouts.marketing>
