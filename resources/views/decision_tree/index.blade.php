<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu de Serviços') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('decision_tree.create') }}" class="btn btn-primary mb-4">{{ __('Adicionar Novo Item') }}</a>
                    <div class="folder-tree">
                        <ul>
                            @foreach($nodes as $node)
                                @include('decision_tree.partials.folder', ['node' => $node])
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-4">
                        <h3>{{ __('Legenda') }}</h3>
                        <ul class="list-inline">
                            <li class="list-inline-item"><i class="fas fa-external-link-alt text-warning"></i> {{ __('Link Externo') }}</li>
                            <li class="list-inline-item"><i class="fas fa-bars text-success"></i> {{ __('Sub-opção') }}</li>
                            <li class="list-inline-item"><i class="far fa-calendar-alt text-primary"></i> {{ __('Agendar') }}</li>
                            <li class="list-inline-item"><i class="far fa-file-alt text-black"></i> {{ __('Instruções') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .folder-tree ul {
            padding-left: 20px;
            border-left: 0.1px solid #ccc;
        }

        .folder-tree li {
            list-style-type: none;
            margin: 5px 0;
            position: relative;
        }

        .folder-tree li::before {
            content: '';
            position: absolute;
            top: 0;
            left: -20px;
            bottom: 0;
            border-left: 0.1px solid #ccc;
        }

        .folder-tree li .folder-label {
            display: flex;
            align-items: center;
            color: black; /* Texto em preto */
        }

        .folder-tree li .folder-label i {
            margin-right: 5px;
        }

        .folder-tree ul ul {
            display: block;
        }

        .legend {
            list-style-type: none;
            padding: 0;
        }

        .legend li {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend i {
            margin-right: 5px;
        }
    </style>
</x-app-layout>
