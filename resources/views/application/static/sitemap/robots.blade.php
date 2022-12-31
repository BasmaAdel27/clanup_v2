Sitemap: {{ route('sitemap') }}
Sitemap: {{ route('sitemap.pages') }}
Sitemap: {{ route('sitemap.events') }}
Sitemap: {{ route('sitemap.groups') }}
Sitemap: {{ route('sitemap.topics') }}
Sitemap: {{ route('sitemap.blogs') }}

User-agent: bingbot
Crawl-delay: 1

User-agent: *
Disallow: */calendar/*atom*
Disallow: */calendar/*rss*
Disallow: */calendar/*xml*

User-agent: *
Disallow: */events/atom/*
Disallow: */events/rss/*
Disallow: */events/xml/*

User-agent: *
Disallow: */rsvps/*atom*
Disallow: */rsvps/*rss*
Disallow: */rsvps/*xml*

User-agent: *
Disallow: */newest/*atom*
Disallow: */newest/*rss*
Disallow: */newest/*xml*

User-agent: *
Disallow: /api/?
Disallow: /api?
Disallow: /*/api/
Disallow: /*/api?
Disallow: /*/api$

User-agent: *
Disallow: /install/?
Disallow: /install?
Disallow: /*/install/
Disallow: /*/install?
Disallow: /*/install$

User-agent: *
Disallow: /update/?
Disallow: /update?
Disallow: /*/update/
Disallow: /*/update?
Disallow: /*/update$

User-agent: *
Disallow: */report_abuse/*