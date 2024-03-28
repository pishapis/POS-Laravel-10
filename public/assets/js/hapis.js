ALERT = function (message, type = '') {

    if (!message) { return; }
    if (typeof message !== 'string') { return; }

    alertPlaceholder = document.getElementById('alertPlaceholder');
    if (!alertPlaceholder) {
        createDiv = document.createElement('div');
        createDiv.setAttribute('id', 'alertPlaceholder');
        document.body.appendChild(createDiv);
        alertPlaceholder = document.getElementById('alertPlaceholder');
    }

    if (type.search(/ok/i) >= 0) {
        isiAlert = /* html */` 
      <div id="isiAlert" 
      style="z-index:2000; position:fixed; top:50px; right:10px;" 
      class="rounded-xl p-2 bg-green-700 text-white flex align-center" >
      <span class="icon-circlecheck text-white text-lg"></span> &nbsp ${message} 
      </div>`;

    } else if (type.search(/bad/i) >= 0) {

        isiAlert = /* html */`<div id="isiAlert" 
        style="z-index:2000; position:fixed; top:50px; right:10px;" 
        class="rounded-xl p-2  bg-red-700 text-white flex align-center" >
        <span class="icon-circleclose2 text-white text-lg"></span> &nbsp ${message}</div>`;
    } else {

        isiAlert = /* html */`<div id="isiAlert" 
      style="z-index:2000;  position:fixed; top:50px; right:10px;" 
      class="rounded-xl p-2 bg-orange-400 text-black  flex align-center" >
        <span class="icon-circlepentung text-black text-lg"></span> &nbsp ${message} 
      </div>`;
    }

    alertPlaceholder.innerHTML = isiAlert;

    isiAlertDiv = document.getElementById('isiAlert');
    isiAlertDiv.classList.add('animate__animated');
    isiAlertDiv.classList.add('animate__fadeInRight');

    setTimeout(function () {
        isiAlertDiv.classList.remove('animate__fadeInRight');
        isiAlertDiv.classList.add('animate__fadeOutRight');
    }, 2000);
};

function rp (angka, prefix) {
    if (parseInt(angka) == NaN) { return 0; }
    if (parseInt(angka) == 0) { return 0; }

    if (!angka) { return }
    minus = false;
    if (angka < 0) {
        minus = true; angka = Math.abs(angka);
    }
    numberString = angka.toString(),
        split = numberString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

    if (minus) {
        return prefix == undefined ? '-' + rupiah : (rupiah ? 'Rp. -' + rupiah : '');
    }
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
};