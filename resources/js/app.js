import "./bootstrap";
import "preline";

import "../../vendor/alperenersoy/filament-export/resources/js/filament-export.js";

document.addEventListener("livewire:load", () => {
    // init pertama
    window.HSStaticMethods?.autoInit();

    // init ulang setiap Livewire selesai render
    if (window.Livewire) {
        Livewire.hook("message.processed", () => {
            window.HSStaticMethods?.autoInit();
        });
    }
});

// Kalau pakai wire:navigate (Filament v3/v4)
document.addEventListener("livewire:navigated", () => {
    window.HSStaticMethods?.autoInit();
});
