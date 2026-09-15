<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Registrar Movimiento</h2>

    <form wire:submit="save">
        
        <!-- Selector de Tipo -->
        <div class="mb-4">
            <select wire:model.live="type" class="w-full rounded-md border-gray-300">
                <option value="expense">Gasto</option>
                <option value="income">Ingreso</option>
                <option value="transfer">Transferencia / Pago de Tarjeta</option>
            </select>
        </div>

        <!-- 🌟 SECCIÓN IA (Solo visible en gastos) 🌟 -->
        @if($type === 'expense')
            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                <label class="block text-sm font-medium text-indigo-800 mb-2">
                    ✨ Autocompletar con IA (Sube una foto de tu ticket)
                </label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <input type="file" wire:model="receipt" accept="image/*"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-600 file:text-white
                                  hover:file:bg-indigo-700
                                  transition-colors cursor-pointer">
                    
                   @if(!empty($receipt))
                        <button type="button" wire:click="analyzeWithAI"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 whitespace-nowrap text-sm font-medium focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            <span wire:loading.remove wire:target="analyzeWithAI">Analizar Ticket</span>
                            <span wire:loading wire:target="analyzeWithAI">Analizando... ⏳</span>
                        </button>
                    @endif
                </div>
                <div wire:loading wire:target="receipt" class="text-sm text-indigo-600 mt-2">
                    Cargando imagen...
                </div>
                @error('receipt') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        @endif
        <!-- 🌟 FIN SECCIÓN IA 🌟 -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            
            <!-- Cuenta Origen -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $type === 'transfer' ? 'Cuenta Origen (De dónde sale)' : 'Cuenta de pago' }}
                </label>
                <select wire:model="wallet_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecciona una cuenta</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
                @error('wallet_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Cuenta Destino (SOLO TRANSFERENCIAS) -->
            @if($type === 'transfer')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cuenta Destino (La que pagas)</label>
                    <select wire:model="to_wallet_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona la cuenta destino</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                    @error('to_wallet_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Categoría (SOLO GASTOS E INGRESOS) -->
            @if($type !== 'transfer')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select wire:model="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Monto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Monto ($)</label>
                <input type="number" step="0.01" wire:model="amount" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="0.00">
                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                <input type="date" wire:model="date" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción o Concepto (Opcional)</label>
                <input type="text" wire:model="description" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Ej. Pago de tarjeta mensual, Supermercado...">
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Botón Guardar -->
        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 flex items-center justify-center min-w-[120px]">
                <span wire:loading.remove wire:target="save">Guardar Movimiento</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>