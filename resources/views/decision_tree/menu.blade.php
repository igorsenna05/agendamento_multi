<div id="menu-{{ $node['id'] }}" class="menu-container" data-parent-id="{{ $node['parent_id'] }}" data-subtitle="{{ $node['label'] }}">
    @foreach($node['children'] as $child)
        <button class="btn btn-primary menu-button" data-id="{{ $child['id'] }}" data-action="{{ $child['action_type'] }}" data-value="{{ $child['action_value'] }}" data-instruction="{{ $child['instruction'] ? $child['instruction']['content'] : '' }}">{{ $child['label'] }}</button>
        @include('decision_tree.menu', ['node' => $child])
    @endforeach
    <button class="btn btn-secondary btn-sm menu-button back-button">Voltar</button>
</div>
