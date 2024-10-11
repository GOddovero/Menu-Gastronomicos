document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#categorias-table")
    .addEventListener("click", function (event) {
      if (event.target.closest(".edit-categoria")) {
        const button = event.target.closest(".edit-categoria");
        const id = button.dataset.id;
        const empresaid = button.dataset.empresaid;
        const descripcion = button.dataset.descripcion;
        const icono = button.dataset.icono;

        document.getElementById("edit-categoria-id").value = id;
        document.getElementById("edit-categoria-empresaid").value = empresaid;
        document.getElementById("categoria-descripcion").value = descripcion;
        document.getElementById("categoria-icono").value = icono;

        // Detecta si el icono pertenece a FontAwesome o Bootstrap
        const iconoClase = icono.startsWith("fa") ? icono : `bi ${icono}`;

        document.getElementById(
          "icono-seleccionado"
        ).innerHTML = `<i class="${iconoClase}" style="font-size: 24px;"></i>`;
      }
    });

  document.querySelectorAll(".icono-opcion").forEach((icono) => {
    icono.addEventListener("click", function () {
      const iconoSeleccionado = this.dataset.icono;

      // Detecta si el icono pertenece a FontAwesome o Bootstrap
      const iconoClase = iconoSeleccionado.startsWith("fa")
        ? iconoSeleccionado
        : `bi ${iconoSeleccionado}`;

      document.getElementById("categoria-icono").value = iconoSeleccionado;
      document.getElementById(
        "icono-seleccionado"
      ).innerHTML = `<i class="${iconoClase}" style="font-size: 24px;"></i>`;
    });
  });

  // Delegación de eventos para el botón de eliminar
  document
    .querySelector("#categorias-table")
    .addEventListener("click", function (event) {
      if (event.target.closest(".delete-categoria")) {
        const button = event.target.closest(".delete-categoria");
        const id = button.dataset.id;
        const empresaid = button.dataset.empresaid;

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
                csrf_token: document
                  .querySelector('meta[name="csrf-token"]')
                  .getAttribute("content"), // Asegúrate de incluir el token CSRF si lo estás usando
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
                    const table = $("#categorias-table").DataTable();
                    table.row(button.closest("tr")).remove().draw(); // Eliminar la fila de la tabla
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
      }
    });
});

function clearCategoriaForm() {
  document.getElementById("edit-categoria-id").value = "";
  document.getElementById("edit-categoria-empresaid").value = "";
  document.getElementById("categoria-descripcion").value = "";
  document.getElementById("categoria-icono").value = "";
}
