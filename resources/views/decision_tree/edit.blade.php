<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('decision_tree.update', $decisionTree->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="label" class="form-label">{{ __('Texto do Menu') }}</label>
                            <input type="text" id="label" name="label" value="{{ $decisionTree->label }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">{{ __('Menu Anterior') }}</label>
                            <select id="parent_id" name="parent_id" class="form-select">
                                <option value="">{{ __('Nenhum') }}</option>
                                @foreach ($parentNodes as $node)
                                    @include('decision_tree.partials.menu_option', ['node' => $node, 'level' => 0, 'selected' => $decisionTree->parent_id])
                                @endforeach
                            </select>
                        <div class="mb-3">
                            <label for="action_type" class="form-label">{{ __('Tipo da Ação') }}</label>
                            <select id="action_type" name="action_type" class="form-select">
                                <option value="sub_option" {{ $decisionTree->action_type == 'sub_option' ? 'selected' : '' }}>{{ __('Sub-opção') }}</option>
                                <option value="external_link" {{ $decisionTree->action_type == 'external_link' ? 'selected' : '' }}>{{ __('Link Externo') }}</option>
                                <option value="schedule" {{ $decisionTree->action_type == 'schedule' ? 'selected' : '' }}>{{ __('Agendar') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="instruction_id" class="form-label">{{ __('Instrução') }}</label>
                            <select id="instruction_id" name="instruction_id" class="form-select">
                                <option value="">{{ __('Nenhum') }}</option>
                                @foreach ($instructions as $instruction)
                                    <option value="{{ $instruction->id }}" {{ $instruction->id == $decisionTree->instruction_id ? 'selected' : '' }}>{{ $instruction->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="label" class="form-label">{{ __('Ação do menu') }}</label>
                            <input type="text" id="action_value" name="action_value" class="form-control" value="{{ $decisionTree->action_value }}">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">{{ __('Posição') }}</label>
                            <input type="number" id="position" name="position" value="{{ $decisionTree->position }}" class="form-control">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                            <a href="{{ route('decision_tree.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                        </div>
                    </form>

                    @if ($decisionTree->children->isEmpty())
                        <button type="button" class="btn btn-danger mt-4" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            {{ __('Excluir') }}
                        </button>
                    @else
                        <div class="alert alert-warning mt-4" role="alert">
                            {{ __('Este registro não pode ser excluído porque possui filhos.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Excluir Nó') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('Tem certeza que deseja excluir este registro? Esta ação não pode ser desfeita.') }}
                </div>
                <div class="modal-footer">
                    <form action="{{ route('decision_tree.destroy', $decisionTree->id) }}" method="POST" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Excluir') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
