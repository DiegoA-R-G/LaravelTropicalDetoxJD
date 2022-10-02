<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Clientes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $Nombre, $Telefono, $Direccion, $Rol;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.clientes.view', [
            'clientes' => Cliente::latest()
						->orWhere('Nombre', 'LIKE', $keyWord)
						->orWhere('Telefono', 'LIKE', $keyWord)
						->orWhere('Direccion', 'LIKE', $keyWord)
						->orWhere('Rol', 'LIKE', $keyWord)
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
		$this->Nombre = null;
		$this->Telefono = null;
		$this->Direccion = null;
		$this->Rol = null;
    }

    public function store()
    {
        $this->validate([
		'Nombre' => 'required',
		'Telefono' => 'required',
		'Direccion' => 'required',
		'Rol' => 'required',
        ]);

        Cliente::create([ 
			'Nombre' => $this-> Nombre,
			'Telefono' => $this-> Telefono,
			'Direccion' => $this-> Direccion,
			'Rol' => $this-> Rol
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Cliente Successfully created.');
    }

    public function edit($id)
    {
        $record = Cliente::findOrFail($id);

        $this->selected_id = $id; 
		$this->Nombre = $record-> Nombre;
		$this->Telefono = $record-> Telefono;
		$this->Direccion = $record-> Direccion;
		$this->Rol = $record-> Rol;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'Nombre' => 'required',
		'Telefono' => 'required',
		'Direccion' => 'required',
		'Rol' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Cliente::find($this->selected_id);
            $record->update([ 
			'Nombre' => $this-> Nombre,
			'Telefono' => $this-> Telefono,
			'Direccion' => $this-> Direccion,
			'Rol' => $this-> Rol
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Cliente Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Cliente::where('id', $id);
            $record->delete();
        }
    }
}
