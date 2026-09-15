<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <livewire:dashboard-stats />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2">
                    <livewire:transaction-form />
                    <livewire:transaction-list />
                </div>

                
               <div class="lg:col-span-1 space-y-6">
                    <livewire:wallet-manager />
                    <livewire:category-manager />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>