<x-app-layout>
    <div class="max-w-7xl mx-auto">
        
        <!-- Estadísticas (Patrimonio y Flujo) -->
        <livewire:dashboard-stats />

        <!-- Historial de Movimientos Recientes -->
        <div class="mt-8">
            <livewire:transaction-list />
        </div>
        
    </div>
</x-app-layout>