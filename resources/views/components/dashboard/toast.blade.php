<div 
    id="toast"
    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-[9999] opacity-0 transition-all duration-300 pointer-events-none"
    x-data="{ 
        show: false,
        message: '',
        type: 'success',
        showToast(msg, isError = false) {
            this.message = msg;
            this.type = isError ? 'error' : 'success';
            this.show = true;
            setTimeout(() => this.show = false, 3000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
>
    <div class="flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl backdrop-blur-sm border border-white/20"
         :class="type === 'error' ? 'bg-gradient-to-r from-red-500 to-pink-600 text-white' : 'bg-gradient-to-r from-green-500 to-emerald-600 text-white'">
        <i class="fas text-lg" :class="type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'"></i>
        <span x-text="message" class="font-medium"></span>
    </div>
</div> 