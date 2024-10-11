document.addEventListener("DOMContentLoaded", function () {
  // Validación del formulario de empresa
  const addEmpresaForm = document.getElementById("addEmpresaForm");
  const editEmpresaForm = document.getElementById("editEmpresaForm");

  // Restaurar la pestaña activa desde localStorage
  const activeTab = localStorage.getItem("activeTab");
  if (activeTab) {
    const tab = document.querySelector(
      `#myTab button[data-bs-target="${activeTab}"]`
    );
    if (tab) {
      const tabInstance = new bootstrap.Tab(tab);
      tabInstance.show();
    }
  }
  // Guardar la pestaña activa en localStorage
  document
    .querySelectorAll('#myTab button[data-bs-toggle="tab"]')
    .forEach((tab) => {
      tab.addEventListener("shown.bs.tab", function (event) {
        const target = event.target.getAttribute("data-bs-target");
        localStorage.setItem("activeTab", target);
      });
    });
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
