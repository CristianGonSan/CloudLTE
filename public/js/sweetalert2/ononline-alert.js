let offlineAlertShown = false;

onoffline = () => {
    if (offlineAlertShown) return;
    offlineAlertShown = true;

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'warning',
        title: 'Conexión perdida',
        text: 'Trabajando sin conexión...',
        showConfirmButton: false,
        background: '#fff3cd',
        color: '#856404',
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

ononline = () => {
    offlineAlertShown = false;
    Swal.close();

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Conexión restablecida',
        showConfirmButton: false,
        timer: 2000,
        background: '#d4edda',
        color: '#155724'
    });
};
