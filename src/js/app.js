
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    const mobileMenuBtn = document.querySelector('#mobile-menu');
    const cerrarMenuBtn = document.querySelector('#cerrar-menu');
    const sidebar = document.querySelector('.sidebar');

    if(mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.add('mostrar');
        });
    }

    if(cerrarMenuBtn) {
        cerrarMenuBtn.addEventListener('click', function() {
            sidebar.classList.add('ocultar');

            setTimeout(() => {
                sidebar.classList.remove('mostrar');
                sidebar.classList.remove('ocultar');
            }, 1000);
        });
    }

    // elimina clase de mostrar en tamanio de tablets y mayores
    const anchoPantalla = document.body.clientWidth;

    // evento cuando se cambia tamanio de pantalla
    window.addEventListener('resize', function() {
        if(anchoPantalla >= 768) {
            sidebar.classList.remove('mostrar');
        }
    })
}
