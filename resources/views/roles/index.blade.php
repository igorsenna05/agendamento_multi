<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfis de Acesso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button onclick="openModal()" class="btn btn-primary mb-4" title="{{ __('Adicionar Perfil de Acesso') }}">
                        <i class="fas fa-plus"></i>
                    </button>
                    <table class="table-auto w-full mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('ID') }}</th>
                                <th class="px-4 py-2">{{ __('Nome') }}</th>
                                <th class="px-4 py-2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody id="roles-table-body">
                            @foreach ($roles as $role)
                                <tr id="role-row-{{ $role->id }}">
                                    <td class="border px-4 py-2">{{ $role->id }}</td>
                                    <td class="border px-4 py-2" id="role-name-{{ $role->id }}">
                                        {{ $role->name }}
                                    </td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <a href="javascript:void(0);" class="btn btn-warning" onclick="editRole({{ $role->id }})" title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
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
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75" onclick="closeModal()"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ __('Adicionar Perfil de Acesso') }}
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <label for="role-name" class="block text-sm font-medium text-gray-700">{{ __('Nome do Perfil de Acesso') }}</label>
                    <input type="text" id="role-name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="addRole()" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                        {{ __('Incluir') }}
                    </button>
                    <button onclick="closeModal()" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm('Você tem certeza que deseja excluir este perfil de acesso?');
        }

        function editRole(id) {
            const nameField = document.getElementById('role-name-' + id);
            if (!nameField) {
                console.error(`Elemento com ID role-${id} não encontrado.`);
                return;
            }

            const currentName = nameField.textContent.trim();

            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.value = currentName;
            nameInput.classList.add('border', 'px-4', 'py-2', 'w-full');


            nameField.innerHTML = '';

            nameField.appendChild(nameInput);
            nameInput.focus();

            nameInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    updateRole(id, nameInput.value);
                }
            });
        }

        function updateRole(id, newName) {
            fetch(`/roles/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: newName })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar a perfil de acesso');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('role-name-' + id).textContent = data.name;
            })
            .catch(error => {
                alert('Erro ao atualizar o perfil de acesso: ' + error.message);
            });
        }

        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function addRole() {
            const name = document.getElementById('role-name').value;

            if (!name) {
                alert('Por favor, insira todos os campos.');
                return;
            }

            fetch('/roles', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: name })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao adicionar o perfil de acesso');
                }
                return response.json();
            })
            .then(data => {
                const newRow = `
                    <tr id="role-row-${data.id}">
                        <td class="border px-4 py-2">${data.id}</td>
                        <td class="border px-4 py-2" id="role-name-${data.id}">${data.name}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="javascript:void(0);" class="btn btn-warning" onclick="editRole(${data.id})" title="{{ __('Editar') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/roles/${data.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                document.getElementById('roles-table-body').insertAdjacentHTML('beforeend', newRow);
                closeModal();
                document.getElementById('role-name').value = '';
            })
            .catch(error => {
                alert('Erro ao adicionar o perfil de acesso: ' + error.message);
            });
        }
    </script>
</x-app-layout>
