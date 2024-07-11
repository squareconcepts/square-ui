document.addEventListener('alpine:init', () => {
    Alpine.data('slideOverPanel', (id) => ({
        id: id,
        showing: false,
        open() {
            document.getElementById('slideover-'+ this.id +'-container').classList.remove('invisible');
            document.getElementById('slideover-'+ this.id +'-bg').classList.remove('opacity-0');
            document.getElementById('slideover-'+ this.id +'-bg').classList.add('opacity-50');
            document.getElementById('slideover-'+ this.id).classList.remove('translate-x-full');
            setTimeout(() => {
                this.showing = !this.showing;
            }, 10)
        },
        close() {
            document.getElementById('slideover-'+ this.id +'-container').classList.add('invisible');
            document.getElementById('slideover-'+ this.id +'-bg').classList.add('opacity-0');
            document.getElementById('slideover-'+ this.id +'-bg').classList.remove('opacity-50');
            document.getElementById('slideover-'+ this.id).classList.add('translate-x-full');
            setTimeout(() => {
                this.showing = !this.showing;
            }, 10)
        },
        clickOutside(show) {
            if(this.showing) {
                this.close();
            }
        }
    }));
})
