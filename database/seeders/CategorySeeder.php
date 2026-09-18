<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Asegúrate de que el namespace de tu modelo sea correcto

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Gastos
            ['name' => 'Alimentación', 'type' => 'expense', 'color' => '#EF4444'], // Rojo
            ['name' => 'Transporte', 'type' => 'expense', 'color' => '#3B82F6'], // Azul
            ['name' => 'Vivienda', 'type' => 'expense', 'color' => '#6366F1'], // Índigo
            ['name' => 'Servicios (Agua, Luz, etc.)', 'type' => 'expense', 'color' => '#EAB308'], // Amarillo
            ['name' => 'Salud', 'type' => 'expense', 'color' => '#14B8A6'], // Verde azulado
            ['name' => 'Entretenimiento', 'type' => 'expense', 'color' => '#A855F7'], // Morado
            ['name' => 'Educación', 'type' => 'expense', 'color' => '#F97316'], // Naranja
            ['name' => 'Compras', 'type' => 'expense', 'color' => '#EC4899'], // Rosa

            // Ingresos
            ['name' => 'Salario', 'type' => 'income', 'color' => '#10B981'], // Esmeralda
            ['name' => 'Inversiones', 'type' => 'income', 'color' => '#06B6D4'], // Cian
        ];

        foreach ($categories as $category) {
            
            Category::firstOrCreate(
                ['name' => $category['name']], 
                [
                    'type' => $category['type'] ?? 'expense', // Ajusta si no tienes columna 'type'
                    'color' => $category['color']
                ]
            );
        }
    }
}