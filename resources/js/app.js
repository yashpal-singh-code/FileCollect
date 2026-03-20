import "./bootstrap";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

window.Alpine = Alpine;
Alpine.plugin(collapse);

/* =========================================
   DARK MODE – RUN BEFORE ALPINE STARTS
   (NO FLICKER, NO WHITE FLASH)
========================================= */
(() => {
    const theme = localStorage.getItem("theme") ?? "system";
    const media = window.matchMedia("(prefers-color-scheme: dark)");

    const applyTheme = () => {
        const isDark =
            theme === "dark" || (theme === "system" && media.matches);

        document.documentElement.classList.toggle("dark", isDark);
    };

    applyTheme();

    /* React to OS theme change (system mode only) */
    media.addEventListener("change", () => {
        const currentTheme = localStorage.getItem("theme") ?? "system";
        if (currentTheme === "system") {
            applyTheme();
        }
    });
})();

/* =========================================
   START ALPINE
========================================= */
Alpine.start();
