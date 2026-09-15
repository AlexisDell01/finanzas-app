<div x-data="{ deleteModalOpen: false, itemToDelete: null }" class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-md font-semibold text-gray-800">Mis Categorías</h3>
    </div>
    
    <div class="p-4">
        <!-- Formulario para agregar -->
        <form wire:submit="save" class="mb-6 space-y-3">
            
            <div class="flex gap-2">
                <select wire:model="type" class="text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 flex-1">
                    <option value="expense">Gasto</option>
                    <option value="income">Ingreso</option>
                </select>
                
                <!-- Selector de color nativo -->
                <input type="color" wire:model="color" class="h-9 w-12 rounded-md border border-gray-300 cursor-pointer p-0.5 bg-white">
            </div>
            
            <div class="flex gap-2">
                <input type="text" wire:model="name" placeholder="Ej. Restaurantes" 
                       class="flex-1 text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400">
                
                <button type="submit" 
                        class="px-3 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                    + Agregar
                </button>
            </div>
            @error('name') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
        </form>

        <!-- Lista de Categorías con scroll interno para que no crezca infinito -->
        <ul class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
            @forelse($categories as $category)
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md border border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $category->color }}"></span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $category->name }}</p>
                            <p class="text-xs text-gray-500">{{ $category->type === 'expense' ? 'Gasto' : 'Ingreso' }}</p>
                        </div>
                    </div>
                    
                    <button type="button" 
                            @click="itemToDelete = {{ $category->id }}; deleteModalOpen = true"
                            class="text-red-500 hover:text-red-700 transition-colors p-1" title="Eliminar categoría">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </li>
            @empty
                <li class="text-sm text-gray-500 text-center py-2">No hay categorías registradas.</li>
            @endforelse
        </ul>
    </div>
    <x-delete-modal 
    title="Eliminar Categoría" 
    message="¿Seguro que deseas eliminar esta categoría? Desaparecerá de tu lista para futuros registros." 
    :itemId="itemToDelete" 
    />
</div>