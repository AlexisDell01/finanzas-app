<!-- Agrega currentId: null -->
<div x-data="{ modalOpen: false, imageUrl: '', currentId: null, deleteModalOpen: false, itemToDelete: null }" class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mt-6">   
     <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800">Movimientos Recientes</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3">Descripción</th>
                    <th class="px-6 py-3">Cuenta</th>
                    <th class="px-6 py-3">Monto</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <!-- Fecha -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                    </td>
                    
                    <!-- Categoría o Etiqueta de Transferencia -->
                    <td class="px-6 py-4">
                        @if($transaction->type === 'transfer')
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700 border border-gray-200 font-medium inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Transferencia
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full font-medium" 
                                style="background-color: {{ $transaction->category->color ?? '#9CA3AF' }}20; color: {{ $transaction->category->color ?? '#9CA3AF' }}">
                                {{ $transaction->category->name ?? 'Sin Categoría' }}
                            </span>
                        @endif
                    </td>
                    
                    <!-- Descripción -->
                    <td class="px-6 py-4 text-gray-900 font-medium">
                        {{ $transaction->description ?? 'Sin descripción' }}
                    </td>
                    
                    <!-- Cuenta (Muestra origen -> destino si es transferencia) -->
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($transaction->type === 'transfer')
                            {{ $transaction->wallet->name ?? '-' }} 
                            <span class="text-gray-400 mx-1">➔</span> 
                            {{ $transaction->toWallet->name ?? '-' }}
                        @else
                            {{ $transaction->wallet->name ?? '-' }}
                        @endif
                    </td>
                    
                    <!-- Monto (Gris para transferencias, Verde +, Rojo -) -->
                    <td class="px-6 py-4 font-bold 
                        {{ $transaction->type === 'expense' ? 'text-red-600' : '' }}
                        {{ $transaction->type === 'income' ? 'text-emerald-600' : '' }}
                        {{ $transaction->type === 'transfer' ? 'text-gray-600' : '' }}
                    ">
                        {{ $transaction->type === 'expense' ? '-' : '' }}
                        {{ $transaction->type === 'income' ? '+' : '' }}
                        ${{ number_format($transaction->amount, 2) }}
                    </td>
                    
                    <!-- Acciones (Ver Ticket y Eliminar) -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            <!-- Botón Ver Ticket -->
                            @if($transaction->receipt_path)
                                <!-- Fíjate que agregamos: currentId = {{ $transaction->id }}; -->
                                <button @click="imageUrl = '{{ Storage::disk('supabase')->temporaryUrl($transaction->receipt_path, now()->addMinutes(15)) }}'; currentId = {{ $transaction->id }}; modalOpen = true"
                                        type="button"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors inline-block" title="Ver Ticket">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            @else
                                <span class="w-8"></span> <!-- Espaciador -->
                            @endif
                            <!-- 🌟 NUEVO: Botón Eliminar 🌟 -->
                            <button type="button" 
                                    @click="itemToDelete = {{ $transaction->id }}; deleteModalOpen = true"
                                    class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Eliminar Movimiento">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Aún no tienes movimientos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Fondo oscuro transparente -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" 
                 @click="modalOpen = false" aria-hidden="true"></div>

            <!-- Truco para centrar el modal en pantallas grandes -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Contenedor de la Imagen -->
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex flex-col items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 w-full text-left" id="modal-title">
                        Vista previa del Ticket
                    </h3>
                    
                    <!-- Imagen cargada dinámicamente -->
                    <div class="w-full flex justify-center bg-gray-50 rounded-lg p-2 border border-gray-100">
                        <img :src="imageUrl" alt="Ticket" class="max-h-[60vh] object-contain rounded-md">
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="modalOpen = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                    
                    <!-- Reemplaza el <a> de "Abrir original" por este <button> -->
                    <button type="button" @click="$wire.downloadReceipt(currentId)" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Descargar Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
    
   <x-delete-modal 
    title="Eliminar Movimiento" 
    message="¿Estás seguro de que deseas eliminar este movimiento? El saldo de tus cuentas se restaurará automáticamente." 
    wire="$wire" 
/>
</div>