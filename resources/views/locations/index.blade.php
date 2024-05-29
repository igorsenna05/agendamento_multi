<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Localizações') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button onclick="openModal()" class="btn btn-primary mb-4" title="{{ __('Adicionar Localização') }}">
                        <i class="fas fa-plus"></i>
                    </button>
                    <table class="table-auto w-full mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('ID') }}</th>
                                <th class="px-4 py-2">{{ __('Nome') }}</th>
                                <th class="px-4 py-2">{{ __('Endereço') }}</th>
                                <th class="px-4 py-2">{{ __('Disponível') }}</th>
                                <th class="px-4 py-2">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody id="locations-table-body">
                            @foreach ($locations as $location)
                                <tr id="location-row-{{ $location->id }}">
                                    <td class="border px-4 py-2">{{ $location->id }}</td>
                                    <td class="border px-4 py-2" id="location-name-{{ $location->id }}">
                                        {{ $location->name }}
                                    </td>
                                    <td class="border px-4 py-2" id="location-address-{{ $location->id }}">
                                        {{ $location->address }}
                                    </td>
                                    <td class="border px-4 py-2" id="location-available-{{ $location->id }}">
                                        {{ $location->is_available ? 'Sim' : 'Não' }}
                                    </td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <a href="javascript:void(0);" class="btn btn-warning" onclick="editLocation({{ $location->id }})" title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
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
                        {{ __('Adicionar Localização') }}
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <label for="location-name" class="block text-sm font-medium text-gray-700">{{ __('Nome da Localização') }}</label>
                    <input type="text" id="location-name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <label for="location-address" class="block text-sm font-medium text-gray-700 mt-2">{{ __('Endereço da Localização') }}</label>
                    <input type="text" id="location-address" name="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <label for="location-available" class="block text-sm font-medium text-gray-700 mt-2">{{ __('Disponível') }}</label>
                    <select id="location-available" name="is_available" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1">{{ __('Sim') }}</option>
                        <option value="0">{{ __('Não') }}</option>
                    </select>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button onclick="addLocation()" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
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
            return confirm('Você tem certeza que deseja excluir esta localização?');
        }

        function editLocation(id) {
            const nameField = document.getElementById('location-name-' + id);
            const addressField = document.getElementById('location-address-' + id);
            const availableField = document.getElementById('location-available-' + id);
            if (!nameField || !addressField || !availableField) {
                console.error(`Elemento com ID location-${id} não encontrado.`);
                return;
            }

            const currentName = nameField.textContent.trim();
            const currentAddress = addressField.textContent.trim();
            const currentAvailable = availableField.textContent.trim() === 'Sim' ? 1 : 0;

            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.value = currentName;
            nameInput.classList.add('border', 'px-4', 'py-2', 'w-full');

            const addressInput = document.createElement('input');
            addressInput.type = 'text';
            addressInput.value = currentAddress;
            addressInput.classList.add('border', 'px-4', 'py-2', 'w-full');

            const availableSelect = document.createElement('select');
            availableSelect.classList.add('border', 'px-4', 'py-2', 'w-full');
            availableSelect.innerHTML = `
                <option value="1" ${currentAvailable == 1 ? 'selected' : ''}>Sim</option>
                <option value="0" ${currentAvailable == 0 ? 'selected' : ''}>Não</option>
            `;

            nameField.innerHTML = '';
            addressField.innerHTML = '';
            availableField.innerHTML = '';

            nameField.appendChild(nameInput);
            addressField.appendChild(addressInput);
            availableField.appendChild(availableSelect);

            nameInput.focus();

            nameInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    updateLocation(id, nameInput.value, addressInput.value, availableSelect.value);
                }
            });
            addressInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    updateLocation(id, nameInput.value, addressInput.value, availableSelect.value);
                }
            });
            availableSelect.addEventListener('change', function() {
                updateLocation(id, nameInput.value, addressInput.value, availableSelect.value);
            });
        }

        function updateLocation(id, newName, newAddress, newAvailable) {
            fetch(`/locations/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: newName, address: newAddress, is_available: newAvailable })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar a localização');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('location-name-' + id).textContent = data.name;
                document.getElementById('location-address-' + id).textContent = data.address;
                document.getElementById('location-available-' + id).textContent = data.is_available ? 'Sim' : 'Não';
            })
            .catch(error => {
                alert('Erro ao atualizar a localização: ' + error.message);
            });
        }

        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function addLocation() {
            const name = document.getElementById('location-name').value;
            const address = document.getElementById('location-address').value;
            const is_available = document.getElementById('location-available').value;

            if (!name || !address) {
                alert('Por favor, insira todos os campos.');
                return;
            }

            fetch('/locations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: name, address: address, is_available: is_available })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao adicionar a localização');
                }
                return response.json();
            })
            .then(data => {
                const newRow = `
                    <tr id="location-row-${data.id}">
                        <td class="border px-4 py-2">${data.id}</td>
                        <td class="border px-4 py-2" id="location-name-${data.id}">${data.name}</td>
                        <td class="border px-4 py-2" id="location-address-${data.id}">${data.address}</td>
                        <td class="border px-4 py-2" id="location-available-${data.id}">${data.is_available ? 'Sim' : 'Não'}</td>
                        <td class="border px-4 py-2 flex space-x-2">
                            <a href="javascript:void(0);" class="btn btn-warning" onclick="editLocation(${data.id})" title="{{ __('Editar') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/locations/${data.id}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();" title="{{ __('Excluir') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                document.getElementById('locations-table-body').insertAdjacentHTML('beforeend', newRow);
                closeModal();
                document.getElementById('location-name').value = '';
                document.getElementById('location-address').value = '';
                document.getElementById('location-available').value = '1';
            })
            .catch(error => {
                alert('Erro ao adicionar a localização: ' + error.message);
            });
        }
    </script>
</x-app-layout>
