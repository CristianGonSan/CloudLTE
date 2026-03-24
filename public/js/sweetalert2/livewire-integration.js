document.addEventListener('DOMContentLoaded', () => {
    Livewire.directive('swal-confirm', ({ el, directive, component, cleanup }) => {
        if (el.__swalConfirmAttached) return;
        el.__swalConfirmAttached = true;

        let content = directive.expression;
        let icon = el.getAttribute('swal-icon') || 'question';

        let onClick = async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            const result = await Swal.fire({
                title: content,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                el.removeEventListener('click', onClick, { capture: true });
                el.click();
                el.addEventListener('click', onClick, { capture: true });
            }
        };

        el.addEventListener('click', onClick, { capture: true });

        cleanup(() => {
            el.removeEventListener('click', onClick, { capture: true });
            el.__swalConfirmAttached = false;
        });
    });

    Livewire.on('sweetalert2', (data) => {
        Swal.fire(data.config);
    });
});


