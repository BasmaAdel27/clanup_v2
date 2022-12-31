<div class="d-flex flex-column border-0">
    <a href="{{ route('blog.show', $blog->slug) }}">
        @if ($blog->blog_category->id)
            <a href="{{ route('blog.tags', $blog->blog_category->slug) }}">
                <span class="badge rounded-sm image-badge border text-uppercase p-2 m-2">
                    {{ $blog->blog_category->name }}
                </span>
            </a>
        @endif
        <a href="{{ route('blog.show', $blog->slug) }}">
            <div class="img-wrap ratio-16-9">
                <div class="img-content">
                    <img class="rounded border" src="{{ $blog->image }}" alt="{{ $blog->name }}" />
                </div>
            </div>
        </a>
    </a>
    <div>
        <h3 class="mb-1">
            <a class="text-truncate text-truncate-two-line text-decoration-none" href="{{ route('blog.show', $blog->slug) }}">{{ $blog->name }}</a>
        </h3>
        <p class="text-truncate text-truncate-two-line text-muted">
            {{ $blog->description }}
        </p>
    </div>
</div>