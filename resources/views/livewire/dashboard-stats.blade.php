<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Tarjeta: Balance Total -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Balance Total</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalBalance, 2) }}</p>
        </div>
    </div>

    <!-- Tarjeta: Ingresos del Mes -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-emerald-50 text-emerald-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Ingresos (Mes)</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($monthlyIncome, 2) }}</p>
        </div>
    </div>

    <!-- Tarjeta: Gastos del Mes -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-red-50 text-red-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Gastos (Mes)</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($monthlyExpenses, 2) }}</p>
        </div>
    </div>
</div>