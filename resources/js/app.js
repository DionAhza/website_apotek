// Buka/tutup elemen (menu mobile, sidebar admin) lewat atribut data-toggle="id-elemen".
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-toggle]');
    if (!trigger) return;

    const target = document.getElementById(trigger.dataset.toggle);
    if (!target) return;

    const isHidden = target.classList.toggle('hidden');
    trigger.setAttribute('aria-expanded', String(!isHidden));
});

// Dialog konfirmasi sebelum aksi berbahaya: <form data-confirm="Pesan...">.
document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-confirm]');
    if (!form || form.dataset.confirmed === '1') return;

    event.preventDefault();
    const message = form.dataset.confirm;

    const proceed = () => {
        form.dataset.confirmed = '1';
        form.submit();
    };

    if (window.Swal) {
        window.Swal.fire({
            title: 'Apakah Anda yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) proceed();
        });
    } else if (window.confirm(message)) {
        proceed();
    }
});

// Pratinjau gambar sebelum diunggah: <input data-image-input="id-img">.
document.addEventListener('change', (event) => {
    const input = event.target.closest('[data-image-input]');
    if (!input || !input.files || !input.files[0]) return;

    const preview = document.getElementById(input.dataset.imageInput);
    if (!preview) return;

    preview.src = URL.createObjectURL(input.files[0]);
    preview.classList.remove('hidden');
    document.getElementById(preview.dataset.placeholder)?.classList.add('hidden');
});
