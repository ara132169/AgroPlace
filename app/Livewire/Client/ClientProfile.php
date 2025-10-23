<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class ClientProfile extends Component
{
    public $client, $tab = 'personal_details';
    public $name, $email, $username, $phone, $address;
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $this->client = Auth::guard('client')->user();
        $this->name = $this->client->name;
        $this->email = $this->client->email;
        $this->username = $this->client->username;
        $this->phone = $this->client->phone;
        $this->address = $this->client->address;
    }

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function updateClientPersonalDetails()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:clients,username,' . $this->client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $this->client->update([
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Datos actualizados correctamente.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($this->current_password, $this->client->password)) {
            session()->flash('error', 'La contraseña actual no es correcta.');
            return;
        }

        $this->client->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Contraseña actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.client.client-profile');
    }
}

