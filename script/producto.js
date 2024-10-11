document.addEventListener("DOMContentLoaded", function () {
  console.log("Productos.js");

  // Delegación de eventos para el botón de editar
  document
    .querySelector("#productos-table")
    .addEventListener("click", function (event) {
      if (event.target.closest(".edit-producto")) {
        const button = event.target.closest(".edit-producto");
        const id = button.dataset.id;
        const categoria_id = button.dataset.categoria_id;
        const nombre = button.dataset.nombre;
        const descripcion = button.dataset.descripcion;
        const tamaño = button.dataset.tamaño;
        const precio = button.dataset.precio;

        document.getElementById("edit-producto-id").value = id;
        document.getElementById("producto-categoria").value = categoria_id;
        document.getElementById("producto-nombre").value = nombre;
        document.getElementById("producto-descripcion").value = descripcion;
        document.getElementById("producto-tamaño").value = tamaño;
        document.getElementById("producto-precio").value = precio;
      }
    });

  // Delegación de eventos para el botón de eliminar
  document
    .querySelector("#productos-table")
    .addEventListener("click", function (event) {
      if (event.target.closest(".delete-producto")) {
        const button = event.target.closest(".delete-producto");
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
                delete_producto: true,
                id: id,
                empresaid: empresaid,
                csrf_token: document
                  .querySelector('meta[name="csrf-token"]')
                  .getAttribute("content"), // Asegúrate de incluir el token CSRF si lo estás usando
              }),
            })
              .then((response) => response.text())
              .then((data) => {
                if (data.includes("Producto eliminado exitosamente")) {
                  Swal.fire(
                    "Eliminado!",
                    "El producto ha sido eliminado.",
                    "success"
                  ).then(() => {
                    const table = $("#productos-table").DataTable();
                    table.row(button.closest("tr")).remove().draw(); // Eliminar la fila de la tabla
                  });
                } else {
                  Swal.fire(
                    "Error!",
                    "Hubo un problema al eliminar el producto.",
                    "error"
                  );
                }
              })
              .catch((error) => {
                console.error("Error:", error);
                Swal.fire(
                  "Error!",
                  "Hubo un problema al eliminar el producto.",
                  "error"
                );
              });
          }
        });
      }
    });
});

function clearProductoForm() {
  document.getElementById("edit-producto-id").value = "";
  document.getElementById("producto-categoria").value = "";
  document.getElementById("producto-nombre").value = "";
  document.getElementById("producto-descripcion").value = "";
  document.getElementById("producto-tamaño").value = "";
  document.getElementById("producto-precio").value = "";
  document.getElementById("producto-imagen").value = "";
}
