/**
 * Theme Manager for HortusFox
 * 
 * Handles light/dark mode switching with system preference detection
 * and localStorage persistence for user overrides.
 */

class ThemeManager {
    constructor() {
        this.storageKey = 'hortusfox-theme';
        this.themeAttribute = 'data-theme';
        this.validThemes = ['light', 'dark', 'auto'];
        
        this.init();
    }

    /**
     * Initialize the theme manager
     */
    init() {
        // Apply saved theme or system preference
        this.applyTheme(this.getStoredTheme() || 'auto');
        
        // Listen for system theme changes
        this.watchSystemTheme();
        
        // Expose methods globally for theme switching
        window.setTheme = (theme) => this.setTheme(theme);
        window.getCurrentTheme = () => this.getCurrentTheme();
        window.toggleTheme = () => this.toggleTheme();
        window.getPreferredTheme = () => this.getCurrentTheme();
    }

    /**
     * Get the stored theme preference from localStorage
     * @returns {string|null} The stored theme or null if not set
     */
    getStoredTheme() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            return this.validThemes.includes(stored) ? stored : null;
        } catch (e) {
            console.warn('Could not access localStorage for theme preference');
            return null;
        }
    }

    /**
     * Store theme preference in localStorage
     * @param {string} theme - The theme to store
     */
    storeTheme(theme) {
        try {
            if (this.validThemes.includes(theme)) {
                localStorage.setItem(this.storageKey, theme);
            }
        } catch (e) {
            console.warn('Could not store theme preference in localStorage');
        }
    }

    /**
     * Get the current effective theme (resolves 'auto' to actual theme)
     * @returns {string} The current effective theme
     */
    getCurrentTheme() {
        const stored = this.getStoredTheme() || 'auto';
        
        if (stored === 'auto') {
            return this.getSystemTheme();
        }
        
        return stored;
    }

    /**
     * Get the system theme preference
     * @returns {string} 'light' or 'dark'
     */
    getSystemTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches 
            ? 'dark' 
            : 'light';
    }

    /**
     * Apply a theme to the document
     * @param {string} theme - The theme to apply
     */
    applyTheme(theme) {
        if (!this.validThemes.includes(theme)) {
            console.warn(`Invalid theme: ${theme}`);
            return;
        }

        const root = document.documentElement;
        
        if (theme === 'auto') {
            // Remove explicit theme attribute to let CSS media queries handle it
            root.removeAttribute(this.themeAttribute);
        } else {
            // Set explicit theme attribute
            root.setAttribute(this.themeAttribute, theme);
        }

        // Store the preference
        this.storeTheme(theme);
        
        // Dispatch custom event for other components to listen to
        this.dispatchThemeChangeEvent(theme);
    }

    /**
     * Set a specific theme
     * @param {string} theme - The theme to set
     */
    setTheme(theme) {
        this.applyTheme(theme);
    }

    /**
     * Toggle between light and dark themes
     */
    toggleTheme() {
        const current = this.getCurrentTheme();
        const newTheme = current === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
    }

    /**
     * Watch for system theme changes and apply if in auto mode
     */
    watchSystemTheme() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            const handleChange = (e) => {
                const stored = this.getStoredTheme() || 'auto';
                if (stored === 'auto') {
                    // Reapply auto theme to pick up system change
                    this.applyTheme('auto');
                }
            };

            // Modern browsers
            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', handleChange);
            } else {
                // Fallback for older browsers
                mediaQuery.addListener(handleChange);
            }
        }
    }

    /**
     * Dispatch a custom theme change event
     * @param {string} theme - The theme that was applied
     */
    dispatchThemeChangeEvent(theme) {
        const event = new CustomEvent('themechange', {
            detail: {
                theme: theme,
                effectiveTheme: this.getCurrentTheme()
            }
        });
        
        document.dispatchEvent(event);
    }

    /**
     * Get theme information for debugging
     * @returns {object} Theme information
     */
    getThemeInfo() {
        return {
            stored: this.getStoredTheme(),
            current: this.getCurrentTheme(),
            system: this.getSystemTheme(),
            isAuto: (this.getStoredTheme() || 'auto') === 'auto'
        };
    }
}

// Initialize theme manager when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ThemeManager();
        initializeThemeToggle();
    });
} else {
    new ThemeManager();
    initializeThemeToggle();
}

/**
 * Initialize the theme toggle switch
 */
function initializeThemeToggle() {
    const themeSwitch = document.getElementById('themeSwitch');
    if (themeSwitch && window.getPreferredTheme) {
        const currentTheme = window.getPreferredTheme();
        themeSwitch.checked = currentTheme === 'dark';
        
        // Listen for theme changes to update switch
        document.addEventListener('themechange', function(e) {
            if (themeSwitch) {
                themeSwitch.checked = e.detail.effectiveTheme === 'dark';
            }
        });
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeManager;
}
