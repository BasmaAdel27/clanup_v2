<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>Daily</changefreq>
        <priority>1</priority>
    </url>
    <url>
        <loc>{{ route('blog') }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>Daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('start.index') }}</loc>
        <changefreq>Daily</changefreq>
        <priority>0.1</priority>
    </url>
    @foreach($pages as $page)
        <url>
            <loc>{{ route('page.show', ['page' => $page->slug]) }}</loc>
            <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>