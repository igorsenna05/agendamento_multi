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
                                <th class="px-4 py-2">{{ __('Código do Serviço') }}</th>
                                <th class="px-4 py-2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody id="services-table-body">
                            @foreach ($services as $service)
                                <tr id="service-row-{{ $service->id }}">
                                    <td class="border px-4 py-2 align-middle" id="service-name-{{ $service->id }}">
                                        <span class="service-text">{{ $service->name }}</span>
                                        <input type="text" class="service-input hidden" value="{{ $service->name }}">
                                    </td>
                                    <td class="border px-4 py-2 align-middle" id="service-code-{{ $service->id }}">
                                        <span class="service-text">{{ $service->service_code }}</span>
                                        <input type="text" class="service-input hidden" value="{{ $service->service_code }}">
                                    </td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <a href="javascript:void(0);" class="btn btn-warning" id="edit-button-{{ $service->id }}" onclick="editService({{ $service->id }})" title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-danger" id="delete-button-{{ $service->id }}" onclick="deleteService({{ $service->id }})" title="{{ __('Excluir') }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-success hidden" id="confirm-edit-button-{{ $service->id }}" onclick="confirmEditService({{ $service->id }})" title="{{ __('Confirmar') }}">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-secondary hidden" id="cancel-edit-button-{{ $service->id }}" onclick="cancelEditService({{ $service->id }})" title="{{ __('Cancelar') }}">
                                            <i class="fas fa-times"></i>
                                        </a>
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
                <div class="mt-2">
                    <label for="service-code" class="block text-sm font-medium text-gray-700">{{ __('Código do Serviço') }}</label>
                    <input type="text" id="service-code" name="service_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mt-4 flex justify-center space-x-2">
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
            const row = document.getElementById('service-row-' + id);
            const nameField = row.querySelector('#service-name-' + id + ' .service-text');
            const codeField = row.querySelector('#service-code-' + id + ' .service-text');
            const nameInput = row.querySelector('#service-name-' + id + ' .service-input');
            const codeInput = row.querySelector('#service-code-' + id + ' .service-input');
            const editButton = row.querySelector('#edit-button-' + id);
            const deleteButton = row.querySelector('#delete-button-' + id);
            const confirmEditButton = row.querySelector('#confirm-edit-button-' + id);
            const cancelEditButton = row.querySelector('#cancel-edit-button-' + id);

            nameField.classList.add('hidden');
            codeField.classList.add('hidden');
            nameInput.classList.remove('hidden');
            codeInput.classList.remove('hidden');

            nameInput.focus();

            editButton.classList.add('hidden');
            deleteButton.classList.add('hidden');
            confirmEditButton.classList.remove('hidden');
            cancelEditButton.classList.remove('hidden');
        }

        function confirmEditService(id) {
            const row = document.getElementById('service-row-' + id);
            const nameField = row.querySelector('#service-name-' + id + ' .service-text');
            const codeField = row.querySelector('#service-code-' + id + ' .service-text');
            const nameInput = row.querySelector('#service-name-' + id + ' .service-input');
            const codeInput = row.querySelector('#service-code-' + id + ' .service-input');
            const editButton = row.querySelector('#edit-button-' + id);
            const deleteButton = row.querySelector('#delete-button-' + id);
            const confirmEditButton = row.querySelector('#confirm-edit-button-' + id);
            const cancelEditButton = row.querySelector('#cancel-edit-button-' + id);

            updateService(id, nameInput.value, codeInput.value);

            nameField.textContent = nameInput.value;
            codeField.textContent = codeInput.value;

            nameField.classList.remove('hidden');
            codeField.classList.remove('hidden');
            nameInput.classList.add('hidden');
            codeInput.classList.add('hidden');

            editButton.classList.remove('hidden');
            deleteButton.classList.remove('hidden');
            confirmEditButton.classList.add('hidden');
            cancelEditButton.classList.add('hidden');
        }

        function cancelEditService(id) {
            const row = document.getElementById('service-row-' + id);
            const nameField = row.querySelector('#service-name-' + id + ' .service-text');
            const codeField = row.querySelector('#service-code-' + id + ' .service-text');
            const nameInput = row.querySelector('#service-name-' + id + ' .service-input');
            const codeInput = row.querySelector('#service-code-' + id + ' .service-input');
            const editButton = row.querySelector('#edit-button-' + id);
            const deleteButton = row.querySelector('#delete-button-' + id);
            const confirmEditButton = row.querySelector('#confirm-edit-button-' + id);
            const cancelEditButton = row.querySelector('#cancel-edit-button-' + id);

            nameField.classList.remove('hidden');
            codeField.classList.remove('hidden');
            nameInput.classList.add('hidden');
            codeInput.classList.add('hidden');

            editButton.classList.remove('hidden');
            deleteButton.classList.remove('hidden');
            confirmEditButton.classList.add('hidden');
            cancelEditButton.classList.add('hidden');
        }

        function updateService(id, newName, newCode) {
            fetch(`/services/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: newName, service_code: newCode })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar o serviço');
                }
                return response.json();
            })
            .then(data => {
                const nameField = document.getElementById('service-name-' + id).querySelector('.service-text');
                const codeField = document.getElementById('service-code-' + id).querySelector('.service-text');
                if (nameField && codeField) {
                    nameField.textContent = data.name;
                    codeField.textContent = data.service_code;
                }
            })
            .catch(error => {
                alert('Erro ao atualizar o serviço: ' + error.message);
            });
        }

        function deleteService(id) {
            if (!confirmDelete()) {
                return;
            }

            fetch(`/services/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao excluir o serviço');
                }
                document.getElementById('service-row-' + id).remove();
            })
            .catch(error => {
                alert('Erro ao excluir o serviço: ' + error.message);
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
            const code = document.getElementById('service-code').value;
            if (!name || !code) {
                alert('Por favor, insira o nome e o código do serviço.');
                return;
            }

            fetch('/services', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: name, service_code: code })
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
                        <td class="border px-4 py-2" id="service-name-${data.id}">
                            <span class="service-text">${data.name}</span>
                            <input type="text" class="service-input hidden" value="${data.name}">
                        </td>
                        <td class="border px-4 py-2" id="service-code-${data.id}">
                            <span class="service-text">${data.service_code}</span>
                            <input type="text" class="service-input hidden" value="${data.service_code}">
                        </td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="javascript:void(0);" class="btn btn-warning" id="edit-button-${data.id}" onclick="editService(${data.id})" title="{{ __('Editar') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger" id="delete-button-${data.id}" onclick="deleteService(${data.id})" title="{{ __('Excluir') }}">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-success hidden" id="confirm-edit-button-${data.id}" onclick="confirmEditService(${data.id})" title="{{ __('Confirmar') }}">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-secondary hidden" id="cancel-edit-button-${data.id}" onclick="cancelEditService(${data.id})" title="{{ __('Cancelar') }}">
                                <i class="fas fa-times"></i>
                            </a>
                        </td>
                    </tr>
                `;
                document.getElementById('services-table-body').insertAdjacentHTML('beforeend', newRow);
                closeModal();
                document.getElementById('service-name').value = '';
                document.getElementById('service-code').value = '';
            })
            .catch(error => {
                alert('Erro ao adicionar o serviço: ' + error.message);
            });
        }
    </script>

    <style>
        .service-input {
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }
        .align-middle {
            vertical-align: middle;
        }
    </style>
</x-app-layout>
