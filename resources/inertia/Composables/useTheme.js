import { ref } from "vue";

const STORAGE_KEY = "theme";
const isDark = ref(false);

function apply(dark) {
    isDark.value = dark;
    document.documentElement.classList.toggle("p-dark", dark);
    try {
        localStorage.setItem(STORAGE_KEY, dark ? "dark" : "light");
    } catch {
        /* abaikan bila storage tidak tersedia */
    }
}

function initTheme() {
    let saved = null;
    try {
        saved = localStorage.getItem(STORAGE_KEY);
    } catch {
        saved = null;
    }
    if (saved === "dark") return apply(true);
    if (saved === "light") return apply(false);
    // Default: ikuti preferensi OS
    const prefersDark =
        typeof window !== "undefined" &&
        typeof window.matchMedia === "function" &&
        window.matchMedia("(prefers-color-scheme: dark)").matches;
    return apply(!!prefersDark);
}

function toggleTheme() {
    apply(!isDark.value);
}

export default function useTheme() {
    return { isDark, initTheme, toggleTheme };
}
