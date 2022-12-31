<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($topic_categories as $topic_category)
        <url>
            <loc>{{ route('find', ['category' => $topic_category->id]) }}</loc>
            <lastmod>{{ $topic_category->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($topics as $topic)
        <url>
            <loc>{{ route('find', ['topic' => $topic->id]) }}</loc>
            <lastmod>{{ $topic->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>