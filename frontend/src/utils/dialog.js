import Swal from 'sweetalert2'

export const notifySuccess = (title = 'Berhasil', text = 'Operasi berhasil dilakukan.') => {
  return Swal.fire({
    icon: 'success',
    title,
    text,
    confirmButtonColor: '#212529',
    confirmButtonText: 'Tutup'
  })
}

export const notifyError = (text = 'Terjadi kesalahan sistem.', title = 'Gagal') => {
  return Swal.fire({
    icon: 'error',
    title,
    text,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Tutup'
  })
}

export const confirmDialog = (text = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan') => {
  return Swal.fire({
    title: 'Konfirmasi',
    text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#212529',
    cancelButtonColor: '#dc3545',
    confirmButtonText: confirmText,
    cancelButtonText: 'Batal'
  })
}
