<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryManager extends Component
{
    public $name;
    public $type = 'expense';
    public $color = '#EF4444'; // Rojo por defecto para gastos

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'type' => $this->type,
            'color' => $this->color,
        ]);

        // Solo limpiamos el nombre, dejamos el tipo y color por si quieres registrar varias seguidas
        $this->reset(['name']); 
    }

    public function delete($id)
    {
        // Validación de seguridad para que solo el dueño pueda borrarla
        Category::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public function render()
    {
        $categories = Category::where('user_id', auth()->id())->latest()->get();
        
        return view('livewire.category-manager', [
            'categories' => $categories
        ]);
    }
}