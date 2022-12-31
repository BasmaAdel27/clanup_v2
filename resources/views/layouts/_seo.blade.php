@php
    SEOMeta::setTitle($seo_title ?? false);
    SEOMeta::setDescription($seo_description ?? get_system_setting('meta_description'));
    SEOMeta::setKeywords($seo_keywords ?? get_system_setting('meta_keywords'));
    SEOMeta::setCanonical($seo_canonical ?? url()->current());
    if (isset($seo_prev_url)) {SEOMeta::addPrev($seo_prev_url);}
    if (isset($seo_next_url)) {SEOMeta::addNext($seo_next_url);}

    OpenGraph::addImage($seo_image ?? asset(get_system_setting('application_logo')));
    OpenGraph::setTitle($seo_title ?? false);
    OpenGraph::setDescription($seo_description ?? get_system_setting('meta_description'));
    OpenGraph::setUrl(url()->current());
    OpenGraph::setSiteName($seo_title ?? get_system_setting('application_name'));
    OpenGraph::addProperty('type', $seo_og_type ?? 'article');

    Artesaos\SEOTools\Facades\TwitterCard::setType('summary_large_image')
        ->setImage($seo_image ?? asset(get_system_setting('application_logo')))
        ->setTitle($seo_title ?? false)
        ->setDescription($seo_description ?? get_system_setting('meta_description'))
        ->setUrl(url()->current())
        ->setSite('@' . str_replace('/', '', parse_url(get_system_setting('twitter_link'), PHP_URL_PATH)));

    Artesaos\SEOTools\Facades\JsonLd::setType('Organization')
        ->setImage($seo_image ?? asset(get_system_setting('application_logo')))
        ->setTitle($seo_title ?? false)
        ->setDescription($seo_description ?? get_system_setting('meta_description'))
        ->setSite(route('home'));
@endphp
{!! SEO::generate() !!}
<script type="application/ld+json">[@stack('additional_json_ld')]</script>