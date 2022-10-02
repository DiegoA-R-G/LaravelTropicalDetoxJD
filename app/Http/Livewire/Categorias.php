<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Categoria;

class Categorias extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $Nombre_Categoria, $img;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.categorias.view', [
            'categorias' => Categoria::latest()
						->orWhere('Nombre_Categoria', 'LIKE', $keyWord)
						->orWhere('img', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->Nombre_Categoria = null;
		$this->img = null;
    }

    public function store()
    {
        $this->validate([
		'Nombre_Categoria' => 'required',
		'img' => 'required',
        ]);

        Categoria::create([ 
			'Nombre_Categoria' => $this-> Nombre_Categoria,
			'img' => $this-> img
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Categoria Successfully created.');
    }

    public function edit($id)
    {
        $record = Categoria::findOrFail($id);

        $this->selected_id = $id; 
		$this->Nombre_Categoria = $record-> Nombre_Categoria;
		$this->img = $record-> img;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'Nombre_Categoria' => 'required',
		'img' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Categoria::find($this->selected_id);
            $record->update([ 
			'Nombre_Categoria' => $this-> Nombre_Categoria,
			'img' => $this-> img
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Categoria Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Categoria::where('id', $id);
            $record->delete();
        }
    }
}
