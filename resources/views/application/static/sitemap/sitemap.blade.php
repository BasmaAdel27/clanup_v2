<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap_events.xml') }}</loc>
        <lastmod>{{ $now }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap_groups.xml') }}</loc>
        <lastmod>{{ $now }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap_topics.xml') }}</loc>
        <lastmod>{{ $now }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap_blogs.xml') }}</loc>
        <lastmod>{{ $now }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap_pages.xml') }}</loc>
        <lastmod>{{ $now }}</lastmod>
    </sitemap>
</sitemapindex>