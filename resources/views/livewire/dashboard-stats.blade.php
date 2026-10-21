<div class="space-y-6 mb-8" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">
    
    <!-- Lógica y Matemáticas -->
    @php
        $flujoNeto = ($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0);
        $limiteTotal = ($creditoDisponible ?? 0) + ($deudaCredito ?? 0);
        $porcentajeUsoCredito = $limiteTotal > 0 ? (($deudaCredito ?? 0) / $limiteTotal) * 100 : 0;

        // Lógica para la Gráfica Moderna (Encontrar el valor más alto para escalar las barras)
        $maxMonto = max($monthlyIncome ?? 0, $monthlyExpenses ?? 0);
        $maxMonto = $maxMonto == 0 ? 1 : $maxMonto; // Prevenir división por cero
        
        $altoIngresos = (($monthlyIncome ?? 0) / $maxMonto) * 100;
        $altoGastos = (($monthlyExpenses ?? 0) / $maxMonto) * 100;
    @endphp

    <!-- ==========================================
         SECCIÓN 1: RESUMEN DE PATRIMONIO 
         ========================================== -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tu Billetera</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Balance Total (Hero - Dark Mode Elegante) -->
            <div class="relative bg-[#0A0A0A] rounded-3xl shadow-xl shadow-gray-900/10 p-6 overflow-hidden border border-gray-800">
                <!-- Brillo moderno tipo Apple -->
                <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-400">Balance Total</p>
                    <h3 class="text-3xl font-bold text-white mt-1 tracking-tight">${{ number_format($balanceTotal ?? 0, 2) }}</h3>
                    <div class="inline-flex items-center gap-1.5 mt-4 px-2.5 py-1 bg-white/10 rounded-full border border-white/5 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="text-xs font-medium text-gray-300">Patrimonio Neto</span>
                    </div>
                </div>
            </div>

            <!-- Liquidez -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden transition-all hover:shadow-md hover:border-gray-200">
                <p class="text-sm font-medium text-gray-500">Liquidez</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($liquidez ?? 0, 2) }}</h3>
                <p class="text-xs text-gray-400 mt-4 font-medium flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Efectivo disponible
                </p>
            </div>

            <!-- Deuda -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden transition-all hover:shadow-md hover:border-gray-200">
                <p class="text-sm font-medium text-gray-500">Deuda actual</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($deudaCredito ?? 0, 2) }}</h3>
                <p class="text-xs text-gray-400 mt-4 font-medium flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span> Tarjetas por pagar
                </p>
            </div>

            <!-- Crédito Disponible -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden flex flex-col justify-between transition-all hover:shadow-md hover:border-gray-200">
                <div>
                    <p class="text-sm font-medium text-gray-500">Crédito Disponible</p>
                    <h3 class="text-2xl font-bold text-indigo-600 mt-1">${{ number_format($creditoDisponible ?? 0, 2) }}</h3>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-[11px] mb-1.5 font-medium text-gray-400">
                        <span>Uso de línea</span>
                        <span>{{ number_format($porcentajeUsoCredito, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000 ease-out" 
                             :style="loaded ? 'width: {{ min($porcentajeUsoCredito, 100) }}%' : 'width: 0%'"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <!-- ==========================================
         SECCIÓN 2: FLUJO Y VISUALIZADOR FINTECH
         ========================================== -->
    <section>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            <!-- Columna Izquierda: Tarjetas de Flujo -->
            <div class="lg:col-span-1 flex flex-col gap-5">
                
                <!-- Ingresos y Gastos en bloque consolidado -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-1 flex flex-col">
                    <!-- Ingresos -->
                    <div class="p-5 flex items-center gap-4 border-b border-gray-50">
                        <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-2xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ingresos</p>
                            <h3 class="text-lg font-bold text-gray-900">${{ number_format($monthlyIncome ?? 0, 2) }}</h3>
                        </div>
                    </div>

                    <!-- Gastos -->
                    <div class="p-5 flex items-center gap-4">
                        <div class="p-3 bg-rose-500/10 text-rose-500 rounded-2xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gastos</p>
                            <h3 class="text-lg font-bold text-gray-900">${{ number_format($monthlyExpenses ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Balance Neto -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden flex-1 flex flex-col justify-center">
                    <p class="text-sm font-medium text-gray-500 mb-1">Ahorro del Mes</p>
                    <h3 class="text-2xl font-bold {{ $flujoNeto >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $flujoNeto >= 0 ? '+' : '' }}${{ number_format($flujoNeto, 2) }}
                    </h3>
                </div>

            </div>

            <!-- Columna Derecha: VISUALIZACIÓN MODERNA (Tailwind Nativo) -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col relative overflow-hidden">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-bold text-gray-800">Flujo de Dinero</h3>
                    <div class="flex gap-3 text-xs font-medium text-gray-400">
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-emerald-400"></div> Entrada</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-rose-400"></div> Salida</span>
                    </div>
                </div>
                
                <!-- Área del Gráfico -->
                <div class="flex-1 w-full flex items-end justify-center gap-12 sm:gap-24 relative mt-8 pb-4">
                    
                    <!-- Líneas de fondo (Grid sutil) -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-4 opacity-30">
                        <div class="w-full h-px bg-gray-200"></div>
                        <div class="w-full h-px bg-gray-200"></div>
                        <div class="w-full h-px bg-gray-200"></div>
                        <div class="w-full h-px bg-gray-200"></div>
                    </div>

                    <!-- Barra Ingresos (Con animación y Glow) -->
                    <div class="relative w-20 sm:w-28 flex flex-col justify-end items-center group z-10 h-full">
                        <!-- Tooltip Flotante Moderno -->
                        <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-xl whitespace-nowrap">
                            ${{ number_format($monthlyIncome ?? 0, 2) }}
                        </div>
                        
                        <!-- La Barra Físcia -->
                        <div class="w-full bg-gradient-to-t from-emerald-500 to-emerald-300 rounded-t-2xl transition-all duration-1000 ease-[cubic-bezier(0.16,1,0.3,1)] shadow-[0_-5px_20px_rgba(52,211,153,0.3)] relative overflow-hidden" 
                             :style="loaded ? 'height: {{ max($altoIngresos, 2) }}%' : 'height: 0%'">
                            <!-- Efecto de brillo de cristal interno -->
                            <div class="absolute inset-0 bg-gradient-to-b from-white/30 to-transparent w-full h-4"></div>
                        </div>
                    </div>

                    <!-- Barra Gastos (Con animación y Glow) -->
                    <div class="relative w-20 sm:w-28 flex flex-col justify-end items-center group z-10 h-full">
                        <!-- Tooltip Flotante Moderno -->
                        <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-xl whitespace-nowrap">
                            ${{ number_format($monthlyExpenses ?? 0, 2) }}
                        </div>

                        <!-- La Barra Física -->
                        <div class="w-full bg-gradient-to-t from-rose-500 to-rose-300 rounded-t-2xl transition-all duration-1000 ease-[cubic-bezier(0.16,1,0.3,1)] shadow-[0_-5px_20px_rgba(244,63,94,0.3)] relative overflow-hidden" 
                             :style="loaded ? 'height: {{ max($altoGastos, 2) }}%' : 'height: 0%'">
                             <!-- Efecto de brillo de cristal interno -->
                             <div class="absolute inset-0 bg-gradient-to-b from-white/30 to-transparent w-full h-4"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <!-- ==========================================
         SECCIÓN 3: TOP GASTOS POR CATEGORÍA
         ========================================== -->
    <section class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-4">Top Gastos del Mes</h2>
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            
            @if(count($gastosPorCategoria) > 0)
                <div class="space-y-6">
                    @php
                        // Obtenemos el gasto mayor para que la barra al 100% sea proporcional a ese
                        $maxGasto = $gastosPorCategoria->max('total');
                        // Paleta de colores premium para cada barra
                        $colores = [
                            'bg-indigo-500 shadow-indigo-500/30', 
                            'bg-purple-500 shadow-purple-500/30', 
                            'bg-pink-500 shadow-pink-500/30', 
                            'bg-rose-500 shadow-rose-500/30', 
                            'bg-orange-500 shadow-orange-500/30'
                        ];
                    @endphp

                    @foreach($gastosPorCategoria as $index => $categoria)
                        @php
                            $porcentaje = $maxGasto > 0 ? ($categoria['total'] / $maxGasto) * 100 : 0;
                            $colorActual = $colores[$index % count($colores)];
                        @endphp
                        
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-700">{{ $categoria['name'] }}</span>
                                <span class="text-sm font-bold text-gray-900">${{ number_format($categoria['total'], 2) }}</span>
                            </div>
                            <!-- Track de fondo -->
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <!-- Barra animada -->
                                <div class="{{ $colorActual }} h-full rounded-full transition-all duration-1000 ease-out shadow-sm" 
                                     :style="loaded ? 'width: {{ $porcentaje }}%' : 'width: 0%'"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-sm font-medium">Aún no hay gastos registrados este mes.</p>
                </div>
            @endif
            
        </div>
    </section>
</div>