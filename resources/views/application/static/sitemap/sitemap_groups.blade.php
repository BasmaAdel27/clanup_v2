<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($groups as $group)
        <url>
            <loc>{{ route('groups.about', ['group' => $group->slug]) }}</loc>
            <lastmod>{{ $group->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>