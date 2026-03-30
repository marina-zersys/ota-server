{{-- Alpine.js confirm modal — glassmorphism --}}
<div x-data="confirmModal()" x-cloak
     @open-confirm.window="open($event.detail)"
     class="relative z-50">
    <template x-if="show">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
            {{-- Modal card --}}
            <div class="relative w-full max-w-md backdrop-blur-2xl bg-white/15 border border-white/20 rounded-2xl shadow-2xl p-6
                        transform transition-all duration-200"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <h3 class="text-lg font-semibold text-white mb-2" x-text="title"></h3>
                <p class="text-sm text-gray-300 mb-6" x-text="message"></p>
                <div class="flex justify-end gap-3">
                    <button @click="close()"
                            class="px-4 py-2 text-sm font-medium text-gray-300 bg-white/10 border border-white/20 rounded-lg hover:bg-white/20 transition">
                        Cancel
                    </button>
                    <form :action="actionUrl" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="_method" :value="method">
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600/80 border border-red-500/30 rounded-lg hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function confirmModal() {
    return {
        show: false,
        title: '',
        message: '',
        actionUrl: '',
        method: 'DELETE',
        open(detail) {
            this.title = detail.title || 'Confirm Delete';
            this.message = detail.message || 'Are you sure? This cannot be undone.';
            this.actionUrl = detail.actionUrl || '';
            this.method = detail.method || 'DELETE';
            this.show = true;
        },
        close() { this.show = false; }
    }
}
</script>
