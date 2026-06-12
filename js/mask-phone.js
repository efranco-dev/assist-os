function maskPhone(e) {
  var v = e.target.value.replace(/\D/g, '');
  v = v.slice(0, 11);
  if (v.length > 10) {
    v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
  } else if (v.length > 5) {
    v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
  } else if (v.length > 2) {
    v = v.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
  }
  e.target.value = v;
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.phone-mask').forEach(function (input) {
    input.addEventListener('input', maskPhone);
  });
});