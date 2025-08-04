<?php

namespace App\Http\Livewire;
use Livewire\Component;

class SettingsSelector extends Component
{
    public function setCurrency($currency)
    {
        session(['currency' => $currency]);
        $this->dispatchBrowserEvent('currency-updated');
    }

    public function setLocale($locale)
    {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        $this->dispatchBrowserEvent('locale-updated');
    }

    public function render()
    {
        return view('livewire.settings-selector');
    }
}
