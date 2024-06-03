<option value="{{ $node->id }}" {{ isset($selected) && $selected == $node->id ? 'selected' : '' }}>
    {{ str_repeat('--', $level) }} {{ $node->label }}
</option>
@if ($node->children)
    @foreach ($node->children as $child)
        @include('decision_tree.partials.menu_option', ['node' => $child, 'level' => $level + 1, 'selected' => $selected ?? null])
    @endforeach
@endif
