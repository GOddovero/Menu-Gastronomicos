document.addEventListener("DOMContentLoaded", function () {
  // Validación del formulario de empresa
  const addEmpresaForm = document.getElementById("addEmpresaForm");
  const editEmpresaForm = document.getElementById("editEmpresaForm");

  function validateForm(form) {
    const descripcion = form.querySelector('[name="empresa_descripcion"]');
    if (descripcion.value.trim() === "") {
      Swal.fire({
        icon: "error",
        title: "Error de validación",
        text: "La descripción es obligatoria",
      });
      return false;
    }
    return true;
  }

  if (addEmpresaForm) {
    addEmpresaForm.addEventListener("submit", function (e) {
      if (!validateForm(this)) {
        e.preventDefault();
      }
    });
  }

  if (editEmpresaForm) {
    editEmpresaForm.addEventListener("submit", function (e) {
      if (!validateForm(this)) {
        e.preventDefault();
      }
    });
  }

  document.querySelectorAll(".edit-empresa").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const descripcion = this.dataset.descripcion;
      const contacto = this.dataset.contacto;
      const email = this.dataset.email;
      const direccion = this.dataset.direccion;
      const whatsapp = this.dataset.whatsapp;
      const instagram = this.dataset.instagram;
      const facebook = this.dataset.facebook;
      const pagina_web = this.dataset.pagina_web;

      // Llenar el formulario de edición con los datos actuales
      document.getElementById("edit-id").value = id;
      document.getElementById("descripcion").value = descripcion;
      document.getElementById("contacto").value = contacto;
      document.getElementById("email").value = email;
      document.getElementById("direccion").value = direccion;
      document.getElementById("whatsapp").value = whatsapp;
      document.getElementById("instagram").value = instagram;
      document.getElementById("facebook").value = facebook;
      document.getElementById("pagina_web").value = pagina_web;

      // Mostrar el modal
      const editModal = new bootstrap.Modal(
        document.getElementById("editEmpresaModal")
      );
      editModal.show();
    });
  });
});
document.querySelectorAll(".edit-categoria").forEach((button) => {
  button.addEventListener("click", function () {
    const id = this.dataset.id;
    const empresaid = this.dataset.empresaid;
    const descripcion = this.dataset.descripcion;

    document.getElementById("edit-categoria-id").value = id;
    document.getElementById("edit-categoria-empresaid").value = empresaid;
    document.getElementById("categoria-descripcion").value = descripcion;
  });
});
function clearCategoriaForm() {
  document.getElementById("edit-categoria-id").value = "";
  document.getElementById("edit-categoria-empresaid").value = "";
  document.getElementById("categoria-descripcion").value = "";
}

document.querySelectorAll(".delete-categoria").forEach((button) => {
  button.addEventListener("click", function () {
    const id = this.dataset.id;
    const empresaid = this.dataset.empresaid;
    const csrfToken = document.querySelector('input[name="csrf_token"]').value;

    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminarlo!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        // Realizar la solicitud de eliminación
        fetch("administrame.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            delete_categoria: true,
            id: id,
            empresaid: empresaid,
            csrf_token: csrfToken,
          }),
        })
          .then((response) => response.text())
          .then((data) => {
            if (data.includes("Categoría eliminada exitosamente")) {
              Swal.fire(
                "Eliminado!",
                "La categoría ha sido eliminada.",
                "success"
              ).then(() => {
                location.reload(); // Recargar la página para reflejar los cambios
              });
            } else {
              Swal.fire(
                "Error!",
                "Hubo un problema al eliminar la categoría.",
                "error"
              );
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            Swal.fire(
              "Error!",
              "Hubo un problema al eliminar la categoría.",
              "error"
            );
          });
      }
    });
  });
});
