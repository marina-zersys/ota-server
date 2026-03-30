{{-- Toast notifications — glassmorphism --}}
<div x-data="toastNotification()" x-cloak class="fixed bottom-6 right-6 z-50 space-y-3">
    <template x-if="visible">
        <div @click="dismiss()"
             class="flex items-center gap-3 px-5 py-3 rounded-xl backdrop-blur-2xl border shadow-2xl cursor-pointer
                    transform transition-all duration-300"
             :class="type === 'success'
                 ? 'bg-emerald-500/20 border-emerald-400/30 text-emerald-200'
                 : 'bg-red-500/20 border-red-400/30 text-red-200'"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4">
            {{-- Icon --}}
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </template>
            <span class="text-sm font-medium" x-text="message"></span>
        </div>
    </template>
</div>

<script>
function toastNotification() {
    return {
        visible: false,
        message: '',
        type: 'success',
        init() {
            @if(session('success'))
                this.show('{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
                this.show('{{ session('error') }}', 'error');
            @endif
        },
        show(msg, t) {
            this.message = msg;
            this.type = t;
            this.visible = true;
            setTimeout(() => this.dismiss(), 4000);
        },
        dismiss() { this.visible = false; }
    }
}
</script>
