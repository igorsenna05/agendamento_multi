<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Serviços') }}
        </h2>
    </x-slot>
    
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button onclick="openModal()" class="btn btn-primary mb-4" title="{{ __('Adicionar Serviço') }}">
                        <i class="fas fa-plus"></i>
                    </button>
                    <table class="table-auto w-full mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('Nome') }}</th>
                                <th class="px-4 py-2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody id="services-table-body">
                            @foreach ($services as $service)
                                <tr id="service-row-{{ $service->id }}">
                                    <td class="border px-4 py-2" id="service-name-{{ $service->id }}">
                                        {{ $service->name }}
                                    </td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <a href="javascript:void(0);" class="btn btn-warning" onclick="editService({{ $service->id }})" title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ __('Adicionar Serviço') }}
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <label for="service-name" class="block text-sm font-medium text-gray-700">{{ __('Nome do Serviço') }}</label>
                    <input type="text" id="service-name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="addService()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        {{ __('Incluir') }}
                    </button>
                    <button onclick="closeModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm('Você tem certeza que deseja excluir este serviço?');
        }

        function editService(id) {
            const nameField = document.getElementById('service-name-' + id);
            if (!nameField) {
                console.error(`Elemento com ID service-name-${id} não encontrado.`);
                return;
            }

            const currentName = nameField.textContent.trim();
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentName;
            input.classList.add('border', 'px-4', 'py-2', 'w-full');

            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    updateService(id, input.value);
                }
            });

            nameField.innerHTML = '';
            nameField.appendChild(input);
            input.focus();
        }

        function updateService(id, newName) {
            fetch(`/services/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: newName })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar o serviço');
                }
                return response.json();
            })
            .then(data => {
                const nameField = document.getElementById('service-name-' + id);
                if (nameField) {
                    nameField.textContent = data.name;
                }
            })
            .catch(error => {
                alert('Erro ao atualizar o serviço: ' + error.message);
            });
        }

        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function addService() {
            const name = document.getElementById('service-name').value;
            if (!name) {
                alert('Por favor, insira um nome para o serviço.');
                return;
            }

            fetch('/services', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: name })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao adicionar o serviço');
                }
                return response.json();
            })
            .then(data => {
                const newRow = `
                    <tr id="service-row-${data.id}">

                        <td class="border px-4 py-2" id="service-name-${data.id}">${data.name}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="javascript:void(0);" class="btn btn-warning" onclick="editService(${data.id})" title="{{ __('Editar') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/services/${data.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                document.getElementById('services-table-body').insertAdjacentHTML('beforeend', newRow);
                closeModal();
                document.getElementById('service-name').value = '';
            })
            .catch(error => {
                alert('Erro ao adicionar o serviço: ' + error.message);
            });
        }
    </script>
</x-app-layout>
