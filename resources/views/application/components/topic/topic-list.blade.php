<ul class="list-inline {{ $container_class }}">
    @foreach ($topics as $topic)
        <li class="list-inline-item mb-2">
            @if ($link)
                <a href="{{ route('find', ['source' => 'EVENTS', 'topic' => $topic->id]) }}">
                    <span class="badge p-2 {{ $badge_class }}">{{ $topic->name }}</span>
                </a>
            @else
                <span class="badge p-2 {{ $badge_class }}">{{ $topic->name }}</span>
            @endif
        </li>
    @endforeach
</ul>