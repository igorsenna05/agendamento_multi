<li>
    <div class="folder-label">
        @if($node->action_type == 'external_link')
            <i class="fas fa-external-link-alt text-warning"></i>
        @elseif($node->action_type == 'sub_option')
            <i class="fas fa-bars text-success"></i>
        @elseif($node->action_type == 'schedule')
            <i class="far fa-calendar-alt text-primary"></i>
        @else
            <i class="fas fa-folder{{ $node->children->isNotEmpty() ? '' : '-open' }}"></i>
        @endif
        <a href="{{ route('decision_tree.edit', $node->id) }}">
            {{ $node->label }}
        </a>
        @if($node->instruction_id)
            <i class="far fa-file-alt text-black"></i>
        @endif
    </div>
    @if($node->children->isNotEmpty())
        <ul>
            @foreach($node->children->sortBy('position') as $child)
                @include('decision_tree.partials.folder', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
