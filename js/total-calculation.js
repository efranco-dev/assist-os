function parseCurrency(value) {
      if (!value) return 0;
      value = value.replace(/\./g, '').replace(/,/g, '.').trim();
      var parsed = parseFloat(value);
      return isNaN(parsed) ? 0 : parsed;
    }

    function formatCurrency(value) {
      return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatCurrencyField(field) {
      if (!field.value.trim()) {
        field.value = '';
        updateTotal();
        return;
      }
      field.value = formatCurrency(parseCurrency(field.value));
      updateTotal();
    }

    function updateTotal() {
      var valorServicoField = document.getElementById('valor_servico');
      var descontoField = document.getElementById('desconto');
      var valorServico = parseCurrency(valorServicoField.value);
      var desconto = parseCurrency(descontoField.value);
      if (!valorServicoField.value.trim() && !descontoField.value.trim()) {
        document.getElementById('valor_total').value = '';
        return;
      }
      var total = valorServico - desconto;
      document.getElementById('valor_total').value = formatCurrency(total >= 0 ? total : 0);
    }

    document.addEventListener('DOMContentLoaded', updateTotal);