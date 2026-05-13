/**
 * Plan Builder - Alpine.js data and keyboard handling
 */

const UNDO_LIMIT = 50;
const AUTOSAVE_DELAY = 2000;
const STORAGE_KEY = 'esagio_plan_builder_backup';

export function planBuilder() {
    return {
        undoStack: [],
        redoStack: [],
        autosaveTimer: null,
        commandQuery: '',

        init() {
            this.restoreFromLocalStorage();
            this.bindKeyboardShortcuts();

            window.addEventListener('plan-updated', () => {
                this.scheduleAutosave();
                this.backupToLocalStorage();
            });

            window.addEventListener('plan-sent', () => {
                this.clearLocalStorageBackup();
            });

            window.addEventListener('copy-to-clipboard', (e) => {
                this.copyToClipboard(e.detail.text);
            });
        },

        bindKeyboardShortcuts() {
            // Keyboard shortcuts are declared on the root element via x-on
            // This method exists for any additional programmatic bindings
        },

        scheduleAutosave() {
            if (this.autosaveTimer) {
                clearTimeout(this.autosaveTimer);
            }

            this.autosaveTimer = setTimeout(() => {
                if (window.Livewire) {
                    const component = window.Livewire.find(
                        document.querySelector('[wire\\:id]')?.getAttribute('wire:id')
                    );
                    if (component) {
                        component.call('autoSave');
                    }
                }
            }, AUTOSAVE_DELAY);
        },

        pushUndo(snapshot) {
            if (this.undoStack.length >= UNDO_LIMIT) {
                this.undoStack.shift();
            }
            this.undoStack.push(snapshot);
            this.redoStack = [];
        },

        undo() {
            if (this.undoStack.length === 0) return;
            const snapshot = this.undoStack.pop();
            this.redoStack.push(snapshot);
            this.applySnapshot(snapshot);
        },

        redo() {
            if (this.redoStack.length === 0) return;
            const snapshot = this.redoStack.pop();
            this.undoStack.push(snapshot);
            this.applySnapshot(snapshot);
        },

        applySnapshot(snapshot) {
            // Snapshots are stored as plain objects; dispatch to Livewire to restore
            if (snapshot && window.Livewire) {
                window.Livewire.dispatch('restore-snapshot', { snapshot });
            }
        },

        backupToLocalStorage() {
            try {
                const data = {
                    timestamp: Date.now(),
                    url: window.location.href,
                };
                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            } catch (_) {
                // Silently fail if storage is full
            }
        },

        restoreFromLocalStorage() {
            try {
                const raw = localStorage.getItem(STORAGE_KEY);
                if (!raw) return;

                const data = JSON.parse(raw);

                if (data.url !== window.location.href) {
                    this.clearLocalStorageBackup();
                    return;
                }

                const age = Date.now() - data.timestamp;
                const ONE_HOUR = 3600000;

                if (age > ONE_HOUR) {
                    this.clearLocalStorageBackup();
                }
            } catch (_) {
                this.clearLocalStorageBackup();
            }
        },

        clearLocalStorageBackup() {
            try {
                localStorage.removeItem(STORAGE_KEY);
            } catch (_) {
                // Silently fail
            }
        },

        copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    this.showCopiedNotification();
                });
            } else {
                const el = document.createElement('textarea');
                el.value = text;
                el.style.position = 'fixed';
                el.style.opacity = '0';
                document.body.appendChild(el);
                el.focus();
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                this.showCopiedNotification();
            }
        },

        showCopiedNotification() {
            // Dispatch a browser event that Alpine toasts can listen to
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message: 'Link copied to clipboard', type: 'success' }
            }));
        },

        fuzzySearch(query, items, keys) {
            if (!query || query.length < 1) return items;

            const q = query.toLowerCase();

            return items.filter(item => {
                return keys.some(key => {
                    const value = (item[key] || '').toString().toLowerCase();
                    return value.includes(q);
                });
            }).sort((a, b) => {
                // Exact match at start ranks higher
                const aStart = keys.some(k => (a[k] || '').toString().toLowerCase().startsWith(q));
                const bStart = keys.some(k => (b[k] || '').toString().toLowerCase().startsWith(q));
                if (aStart && !bStart) return -1;
                if (!aStart && bStart) return 1;
                return 0;
            });
        },

        get commandResults() {
            if (!this.commandQuery) return [];

            const steps = [
                { id: 1, label: 'Patient' },
                { id: 2, label: 'Diagnosis' },
                { id: 3, label: 'Treatment Plan' },
                { id: 4, label: 'Visuals' },
                { id: 5, label: 'Content' },
                { id: 6, label: 'Video Consultation' },
                { id: 7, label: 'Settings' },
                { id: 8, label: 'Review and Send' },
            ];

            return this.fuzzySearch(this.commandQuery, steps, ['label']);
        },
    };
}

// Register globally so Blade can use x-data="planBuilder()"
window.planBuilder = planBuilder;
