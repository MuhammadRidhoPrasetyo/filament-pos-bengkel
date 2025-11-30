<div x-data="toast()" x-init="init()" @notification.window="add($event.detail)"
    class="fixed top-4 right-4 z-[9999] space-y-3 w-96 max-w-[92vw] pointer-events-none" aria-live="polite"
    aria-atomic="true">
    <template x-for="t in items" :key="t.id">
        <div x-show="t.show" x-transition.opacity.scale.duration.200
            class="pointer-events-auto rounded-lg border-t-2 p-4 shadow-lg" :class="classes(t.type)" role="alert"
            @click="remove(t.id)">
            <div class="flex">
                <span class="inline-flex justify-center items-center size-8 rounded-full border-4"
                    :class="iconWrapClasses(t.type)">
                    <!-- icon -->
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
                <div class="ms-3">
                    <h3 class="font-semibold" :class="titleClasses(t.type)" x-text="t.title"></h3>
                    <p class="text-sm" :class="textClasses(t.type)" x-text="t.message"></p>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toast', () => ({
            items: [],
            init() {},

            add({
                type = 'success',
                title = 'Successfully updated.',
                message = 'Action completed.',
                timeout = 4000
            } = {}) {
                const id = crypto.randomUUID ? crypto.randomUUID() : String(Date.now() + Math
                    .random());
                const t = {
                    id,
                    type,
                    title,
                    message,
                    show: true
                };
                this.items.push(t);
                setTimeout(() => this.remove(id), timeout);
            },

            remove(id) {
                const i = this.items.findIndex(it => it.id === id);
                if (i > -1) this.items.splice(i, 1);
            },

            // style helpers
            classes(type) {
                return ({
                    success: 'bg-teal-50 border-teal-500 dark:bg-teal-800/30 dark:border-teal-500',
                    info: 'bg-blue-50 border-blue-500 dark:bg-blue-800/30 dark:border-blue-500',
                    warning: 'bg-amber-50 border-amber-500 dark:bg-amber-800/30 dark:border-amber-500',
                    error: 'bg-rose-50 border-rose-500 dark:bg-rose-800/30 dark:border-rose-500',
                })[type] || this.classes('success');
            },
            iconWrapClasses(type) {
                return ({
                    success: 'border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-400',
                    info: 'border-blue-100 bg-blue-200 text-blue-800 dark:border-blue-900 dark:bg-blue-800 dark:text-blue-400',
                    warning: 'border-amber-100 bg-amber-200 text-amber-800 dark:border-amber-900 dark:bg-amber-800 dark:text-amber-400',
                    error: 'border-rose-100 bg-rose-200 text-rose-800 dark:border-rose-900 dark:bg-rose-800 dark:text-rose-400',
                })[type] || this.iconWrapClasses('success');
            },
            titleClasses(type) {
                return ({
                    success: 'text-gray-800 dark:text-white',
                    info: 'text-gray-800 dark:text-white',
                    warning: 'text-gray-800 dark:text-white',
                    error: 'text-gray-800 dark:text-white',
                })[type];
            },
            textClasses(type) {
                return ({
                    success: 'text-gray-700 dark:text-neutral-400',
                    info: 'text-gray-700 dark:text-neutral-400',
                    warning: 'text-gray-700 dark:text-neutral-400',
                    error: 'text-gray-700 dark:text-neutral-400',
                })[type];
            },
        }));
    });
</script>
