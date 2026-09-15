<div x-data="{ deleteModalOpen: false, itemToDelete: null }" class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-md font-semibold text-gray-800">Mis Cuentas</h3>
    </div>
    
    <div class="p-4">
        <!-- Formulario para agregar -->
        <form wire:submit="save" class="mb-6 space-y-3">
            
            <div class="flex gap-2">
                <select wire:model.live="type" class="w-1/3 text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="standard">Débito / Efectivo</option>
                    <option value="credit">Tarjeta de Crédito</option>
                </select>
                
                <div class="flex-1">
                    <input type="text" wire:model="name" placeholder="Nombre (Ej. Nu, BBVA)" 
                           class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400">
                </div>
            </div>

            <div class="flex gap-2">
                @if($type === 'credit')
                    <div class="flex-1">
                        <input type="number" step="0.01" wire:model="credit_limit" placeholder="Límite de Crédito ($)" 
                               class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400">
                    </div>
                @endif
                
                <div class="flex-1">
                    <!-- Nota visual para ayudar a la captura -->
                    <input type="number" step="0.01" wire:model="balance" placeholder="{{ $type === 'credit' ? 'Deuda Actual (En negativo -)' : 'Saldo Actual ($)' }}" 
                           class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400">
                </div>
            </div>

            <!-- Validaciones -->
            <div class="text-xs text-red-500">
                @error('name') <span>{{ $message }}</span> <br> @enderror
                @error('credit_limit') <span>{{ $message }}</span> <br> @enderror
                @error('balance') <span>{{ $message }}</span> @enderror
            </div>
            
            <button type="submit" 
                    class="w-full px-3 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                + Agregar Cuenta
            </button>
        </form>

        <!-- Lista de Cuentas -->
        <ul class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
            @forelse($wallets as $wallet)
                <li class="flex justify-between items-center p-3 bg-gray-50 rounded-md border border-gray-100">
                    <div class="w-full mr-2">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-gray-800">{{ $wallet->name }}</p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $wallet->type === 'credit' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $wallet->type === 'credit' ? 'Crédito' : 'Estándar' }}
                            </span>
                        </div>
                        
                       @if($wallet->type === 'credit')
                        <div class="mt-1 flex justify-between text-xs">
                            <span class="text-gray-500">Límite: ${{ number_format($wallet->credit_limit, 2) }}</span>
                            <span class="text-emerald-600 font-semibold">
                                Disponible: ${{ number_format($wallet->credit_limit + $wallet->balance, 2) }}
                            </span>
                        </div>
                        
                        <!-- 🌟 NUEVA LÓGICA DE VISUALIZACIÓN DE DEUDA 🌟 -->
                        @if($wallet->balance < 0)
                            <!-- Si es negativo, usamos abs() para quitar el signo menos visualmente pero decir que es Deuda -->
                            <div class="text-xs text-red-500">Deuda: ${{ number_format(abs($wallet->balance), 2) }}</div>
                        @elseif($wallet->balance > 0)
                            <div class="text-xs text-indigo-600">Saldo a favor: ${{ number_format($wallet->balance, 2) }}</div>
                        @else
                            <div class="text-xs text-gray-500">Sin deuda ($0.00)</div>
                        @endif
                    @else
                            <p class="text-xs text-emerald-600 font-semibold mt-1">
                                Saldo: ${{ number_format($wallet->balance, 2) }}
                            </p>
                        @endif
                    </div>
                    
                    <button type="button" 
                            @click="itemToDelete = {{ $wallet->id }}; deleteModalOpen = true"
                            class="text-red-500 hover:text-red-700 transition-colors p-1" title="Eliminar cuenta">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </li>
            @empty
                <li class="text-sm text-gray-500 text-center py-2">No hay cuentas registradas.</li>
            @endforelse
        </ul>
    </div>
    
    <x-delete-modal 
    title="Eliminar Cuenta" 
    message="¿Seguro que deseas eliminar esta cuenta? Los movimientos asociados seguirán existiendo, pero no estarán vinculados a esta cuenta." 
    :itemId="itemToDelete" 
    />
</div>