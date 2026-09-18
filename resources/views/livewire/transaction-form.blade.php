<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    
    <!-- Título con Icono -->
    <div class="flex items-center gap-3 mb-8">
        <div class="p-2.5 bg-slate-900 text-white rounded-xl shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Registrar Movimiento</h2>
    </div>

    <form wire:submit="save">
        
        <!-- Selector de Tipo (Principal) -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">¿Qué tipo de movimiento es?</label>
            <select wire:model.live="type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all cursor-pointer">
                <option value="expense">📉 Gasto (Salida de dinero)</option>
                <option value="income">📈 Ingreso (Entrada de dinero)</option>
                <option value="transfer">🔄 Transferencia / Pago de Tarjeta</option>
            </select>
        </div>

        <!-- 🌟 SECCIÓN IA (Solo visible en gastos) 🌟 -->
        @if($type === 'expense')
            <div class="mb-8 relative overflow-hidden bg-gradient-to-br from-indigo-50 via-purple-50 to-indigo-50 border border-indigo-100/60 rounded-2xl p-6">
                <!-- Icono de fondo decorativo -->
                <div class="absolute -right-4 -top-4 opacity-10">
                    <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15 9l7 1-5 5 1 7-7-4-7 4 1-7-5-5 7-1z"></path></svg>
                </div>
                
                <div class="relative z-10">
                    <label class="flex items-center text-sm font-bold text-indigo-900 mb-3 gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Autocompletar con IA
                    </label>
                    <p class="text-xs text-indigo-700/80 mb-4 font-medium">Sube una foto de tu ticket y llenaremos los datos por ti.</p>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="receipt" accept="image/*"
                                   class="block w-full text-sm text-indigo-800
                                          file:mr-4 file:py-2.5 file:px-5
                                          file:rounded-xl file:border-0
                                          file:text-sm file:font-bold
                                          file:bg-white file:text-indigo-600 file:shadow-sm
                                          hover:file:bg-indigo-50
                                          transition-all cursor-pointer focus:outline-none">
                        </div>
                        
                        @if(!empty($receipt))
                            <button type="button" wire:click="analyzeWithAI"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 hover:shadow-md whitespace-nowrap text-sm font-semibold focus:ring-4 focus:ring-indigo-500/20 transition-all flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="analyzeWithAI">Analizar Ticket</span>
                                <span wire:loading wire:target="analyzeWithAI" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Procesando...
                                </span>
                            </button>
                        @endif
                    </div>
                    
                    <div wire:loading wire:target="receipt" class="text-xs font-medium text-indigo-600 mt-3 flex items-center gap-1.5">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Cargando imagen...
                    </div>
                    @error('receipt') <span class="text-red-500 text-xs font-medium mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif
        <!-- 🌟 FIN SECCIÓN IA 🌟 -->

        <!-- Grid de Inputs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- Cuenta Origen -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $type === 'transfer' ? 'Cuenta Origen (De dónde sale)' : 'Cuenta de pago' }}
                </label>
                <select wire:model="wallet_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-colors cursor-pointer">
                    <option value="">Selecciona una cuenta</option>
                    @foreach($wallets as $wallet)
                        <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                    @endforeach
                </select>
                @error('wallet_id') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <!-- Cuenta Destino (SOLO TRANSFERENCIAS) -->
            @if($type === 'transfer')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cuenta Destino (La que pagas)</label>
                    <select wire:model="to_wallet_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-colors cursor-pointer">
                        <option value="">Selecciona la cuenta destino</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                    @error('to_wallet_id') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Categoría (SOLO GASTOS E INGRESOS) -->
            @if($type !== 'transfer')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select wire:model="category_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-colors cursor-pointer">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Monto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Monto</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 font-medium">$</span>
                    </div>
                    <input type="number" step="0.01" wire:model="amount" 
                           class="w-full pl-8 pr-4 py-3.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block transition-colors"
                           placeholder="0.00">
                </div>
                @error('amount') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                <input type="date" wire:model="date" 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-colors cursor-pointer">
                @error('date') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
            </div>
            
            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción o Concepto (Opcional)</label>
                <input type="text" wire:model="description" 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-colors"
                       placeholder="Ej. Cena con amigos, Supermercado...">
                @error('description') <span class="text-xs font-medium text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Botón Guardar -->
        <div class="mt-8 flex justify-end">
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-900/10 transition-all flex items-center justify-center min-w-[160px] gap-2 shadow-sm hover:shadow">
                <span wire:loading.remove wire:target="save">Guardar Movimiento</span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Guardando...
                </span>
            </button>
        </div>
    </form>
</div>