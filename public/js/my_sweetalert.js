$('#tablePembelian').on('click', '.btn-delete', function(e){
    e.preventDefault();
    let form = $(this).parents('form');
    swal({
        title: 'Apakah kamu yakin?',
        text: 'Data yang sudah dihapus tidak bisa dikembalikan lagi!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
        buttons: ['Batal', 'Ok']
      })
      .then((willDelete) => {
        if (willDelete) {
            swal({
                title: 'Data dihapus!',
                text: 'Redirecting...',
                icon: 'success',
                timer: 2000,
                buttons: false,
            }).then(() => {
                form.submit();
            })
        }
      });
});

$('.btn-delete').click(function(e){
    e.preventDefault();
    let form = $(this).parents('form');
    swal({
        title: 'Konfirmasi',
        text: 'Hapus produk?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
        buttons: ['Batal', 'Ok']
      })
      .then((willDelete) => {
        if (willDelete) {
            form.submit();
        }
      });
});

$('#tablePembelian').on('click', '.btn-acc', function(e){
  e.preventDefault();
  let form = $(this).parents('form');
  swal({
      title: 'Apakah kamu yakin?',
      text: 'Data yang di acc akan masuk ke produk siap dijual',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
      buttons: ['Batal', 'Ok']
    })
    .then((willDelete) => {
      if (willDelete) {
          swal({
              title: 'Data diacc!',
              text: 'Redirecting...',
              icon: 'success',
              timer: 2000,
              buttons: false,
          }).then(() => {
              form.submit();
          })
      }
    });
});

$('.btn-acc').click(function(e){
  e.preventDefault();
  let form = $(this).parents('form');
  swal({
      title: 'Konfirmasi',
      text: 'acc produk?',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
      buttons: ['Batal', 'Ok']
    })
    .then((willDelete) => {
      if (willDelete) {
          form.submit();
      }
    });
});

$('#tableKu').on('click', '.btn-retur', function(e){
  e.preventDefault();
  let form = $(this).parents('form');
  swal({
      title: 'Apakah kamu yakin?',
      text: 'Barang yang di retur akan kembali ke stok produk',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
      buttons: ['Batal', 'Ok']
    })
    .then((willDelete) => {
      if (willDelete) {
          swal({
              title: 'Barang berhasil retur!',
              text: 'Redirecting...',
              icon: 'success',
              timer: 2000,
              buttons: false,
          }).then(() => {
              form.submit();
          })
      }
    });
});

$('.btn-retur').click(function(e){
  e.preventDefault();
  let form = $(this).parents('form');
  swal({
      title: 'Konfirmasi',
      text: 'Retur Barang?',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
      buttons: ['Batal', 'Ok']
    })
    .then((willDelete) => {
      if (willDelete) {
          form.submit();
      }
    });
});
