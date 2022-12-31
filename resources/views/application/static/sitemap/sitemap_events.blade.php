<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($events as $event)
        <url>
            <loc>{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}</loc>
            <lastmod>{{ $event->updated_at->toAtomString() }}</lastmod>
            <changefreq>Daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>