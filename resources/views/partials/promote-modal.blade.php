{{-- Alpine.js promote modal — glassmorphism --}}
<div x-data="promoteModal()" x-cloak
     @open-promote.window="open($event.detail)"
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
                <h3 class="text-lg font-semibold text-white mb-2">Promote Release</h3>
                <p class="text-sm text-gray-300 mb-4">Select the target environment to promote this release to.</p>
                <form :action="'/dashboard/releases/' + releaseId + '/promote'" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Target Environment</label>
                        <select name="environment" class="w-full rounded-lg text-sm px-3 py-2 bg-white/10 border border-white/20 text-white focus:ring-lime-500 focus:border-lime-500 [&>option]:bg-slate-800 [&>option]:text-white">
                            <template x-for="env in availableEnvs" :key="env">
                                <option :value="env" x-text="env"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="close()"
                                class="px-4 py-2 text-sm font-medium text-gray-300 bg-white/10 border border-white/20 rounded-lg hover:bg-white/20 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600/80 border border-blue-500/30 rounded-lg hover:bg-blue-600 transition">
                            Promote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
function promoteModal() {
    return {
        show: false,
        releaseId: null,
        currentEnv: '',
        allEnvs: ['dev', 'staging', 'prod'],
        get availableEnvs() {
            return this.allEnvs.filter(e => e !== this.currentEnv);
        },
        open(detail) {
            this.releaseId = detail.releaseId;
            this.currentEnv = detail.currentEnv || '';
            this.show = true;
        },
        close() { this.show = false; }
    }
}
</script>
