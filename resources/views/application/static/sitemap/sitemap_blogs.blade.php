<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($blog_tags as $tag)
        <url>
            <loc>{{ route('blog.tags', ['blog_category' => $tag->slug]) }}</loc>
            <lastmod>{{ $tag->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($blogs as $blog)
        <url>
            <loc>{{ route('blog.show', ['blog' => $blog->slug]) }}</loc>
            <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>