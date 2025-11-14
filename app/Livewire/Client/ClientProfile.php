<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;

class ClientProfile extends Component
{
    use WithFileUploads;
    
    public $client, $tab = 'personal_details';
    public $name, $email, $username, $phone, $address;
    public $current_password, $new_password, $new_password_confirmation;
    public $profilePicture;

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

    public function updatedProfilePicture()
    {
        $this->validate([
            'profilePicture' => 'image|max:2048', // Max 2MB
        ]);

        try {
            // Eliminar imagen anterior si existe
            if ($this->client->picture && Storage::disk('public')->exists($this->client->picture)) {
                Storage::disk('public')->delete($this->client->picture);
            }

            // Guardar nueva imagen
            $path = $this->profilePicture->store('client-profiles', 'public');
            
            // Actualizar cliente
            $this->client->update([
                'picture' => $path
            ]);

            // Refrescar la instancia del cliente
            $this->client = $this->client->fresh();
            
            session()->flash('success', 'Foto de perfil actualizada correctamente.');
            $this->dispatch('imageUploaded', 'Foto de perfil actualizada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir la imagen. Intenta de nuevo.');
            $this->dispatch('imageError', 'Error al subir la imagen. Intenta de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.client.client-profile');
    }
}

