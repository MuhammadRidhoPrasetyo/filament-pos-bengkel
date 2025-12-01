// resources/js/filament/admin/theme.js

import '@preline/select'; // penting untuk data-hs-select
import 'preline';         // optional tapi biasanya sekalian dipakai

// init pertama (saat halaman pertama kali load)
document.addEventListener('DOMContentLoaded', () => {
    window.HSStaticMethods?.autoInit();
});

// kalau pakai Livewire (Filament) - setiap request selesai, re-init
document.addEventListener('livewire:load', () => {
    window.HSStaticMethods?.autoInit();

    if (window.Livewire) {
        Livewire.hook('message.processed', () => {
            window.HSStaticMethods?.autoInit();
        });
    }
});

// kalau pakai wire:navigate / livewire:navigated
document.addEventListener('livewire:navigated', () => {
    window.HSStaticMethods?.autoInit();
});
