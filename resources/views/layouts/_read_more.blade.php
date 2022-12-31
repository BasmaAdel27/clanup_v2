@push('page_body_scripts')
    <style>
        .readmore {
            position: relative;
            max-height: 200px;
            overflow: hidden;
            padding-bottom: 20px;
            margin-bottom: 20px;
            transition: max-height 0.15s ease-out;
        }

        .readmore.expand {
            max-height: 5000px !important;
            transition: max-height 0.35s ease-in-out;
        }

        .readmore-link {
            position: absolute;
            bottom: -10px;
            right: 0;
            display: block;
            width: 100%;
            height: 60px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding-top: 40px;
            padding-bottom: 30px;
            background-image: linear-gradient(to bottom, transparent, {{ isset($gradient) ? $gradient : '#f8f9fa' }});
            cursor: pointer;
        }

        .readmore-link.expand {
            position: relative;
            background-image: none;
            padding-top: 10px;
            height: 20px;
        }

        .readmore-link:after {
            content: "{{ __('Show more') }}";
        }

        .readmore-link.expand:after {
            content: "{{ __('Show less') }}";
        }
    </style>
@endpush
 
<div class="{{ isset($class) ? $class : '' }} readmore">
    {!! html_entity_decode($content) !!}
    <span class="readmore-link text-primary"></span>   
</div>