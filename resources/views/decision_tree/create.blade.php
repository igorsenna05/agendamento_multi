<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adicionar Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('decision_tree.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="label" class="form-label">{{ __('Texto do Menu') }}</label>
                            <input type="text" id="label" name="label" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">{{ __('Menu Anterior') }}</label>
                            <select id="parent_id" name="parent_id" class="form-select">
                                <option value="">{{ __('Nenhum') }}</option>
                                @foreach ($parentNodes as $node)
                                    <option value="{{ $node->id }}">{{ $node->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="action_type" class="form-label">{{ __('Tipo da Ação') }}</label>
                            <select id="action_type" name="action_type" class="form-select">
                                <option value="sub_option">{{ __('Sub-opção') }}</option>
                                <option value="external_link">{{ __('Link Externo') }}</option>
                                <option value="schedule">{{ __('Agendar') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="instruction_id" class="form-label">{{ __('Instrução') }}</label>
                            <select id="instruction_id" name="instruction_id" class="form-select">
                                <option value="">{{ __('Nenhum') }}</option>
                                @foreach ($instructions as $instruction)
                                    <option value="{{ $instruction->id }}">{{ $instruction->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="label" class="form-label">{{ __('Ação do menu') }}</label>
                            <input type="text" id="action_value" name="action_value" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">{{ __('Posição') }}</label>
                            <input type="number" id="position" name="position" class="form-control">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                            <a href="{{ route('decision_tree.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
