    document.addEventListener('DOMContentLoaded', function () {
      var deleteLinks = document.querySelectorAll('.delete-link');
      var confirmModalEl = document.getElementById('confirmDeleteModal');
      var confirmDeleteButton = document.getElementById('confirmDeleteButton');
      var deleteHref = null;

      if (!confirmModalEl || !confirmDeleteButton) {
        return;
      }

      var confirmModal = new bootstrap.Modal(confirmModalEl);

      deleteLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
          event.preventDefault();
          deleteHref = link.href;
          confirmModal.show();
        });
      });

      confirmDeleteButton.addEventListener('click', function () {
        if (deleteHref) {
          window.location.href = deleteHref;
        }
      });
    });