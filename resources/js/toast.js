import Swal from 'sweetalert2';

const Toast = Swal.mixin({
  toast: true,
  position: 'bottom-end',
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  customClass: {
    popup: 'shadow-sm border-0 rounded', // Clases de Bootstrap
  },
  didOpen: (toast) => {
    toast.style.background = '#ffffff'; // Fondo limpio
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  }
});


export const notify = (icon, title) => {
    Swal.mixin({
        toast: true,
        position: 'bottom-end', // Posición abajo
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        icon: icon // 'success', 'error', 'warning', 'info'
    }).fire({
        title: title
    });
};

export const confirmDelete = async (title = "¿Estás seguro?") => {
    const result = await Swal.fire({
        title: title,
        text: "Al confirmar, se eliminará toda la información asociada.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Rojo de Bootstrap
        cancelButtonColor: '#6c757d',  // Gris de Bootstrap
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    return result.isConfirmed; // Devuelve true si el usuario aceptó
};