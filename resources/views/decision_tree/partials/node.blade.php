<li>
    <a href="{{ route('decision_tree.edit', $node->id) }}">
        @if ($node->action_type === 'external_link')
            <i class="fas fa-link text-yellow-500 w-6 h-6 inline"></i>
        @elseif ($node->action_type === 'schedule')
            <i class="fas fa-calendar-alt text-blue-500 w-6 h-6 inline"></i>
        @elseif ($node->action_type === 'sub_option')
            <i class="fas fa-sitemap text-green-500 w-6 h-6 inline"></i>
        @endif
        {{ $node->label }}
    </a>
    @if($node->children->isNotEmpty())
        <ul>
            @foreach($node->children->sortBy('position') as $child)
                @include('decision_tree.partials.node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
