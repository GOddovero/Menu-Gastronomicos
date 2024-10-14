const menuData = {};

function crearBotonesCategorias(menuData) {
  const buttonContainer = document.getElementById("category-buttons");
  for (const categoria in menuData) {
    const button = document.createElement("button");
    button.textContent = categoria;
    button.className = "btn category-btn";
    button.addEventListener("click", () => {
      document
        .querySelectorAll(".category-btn")
        .forEach((btn) => btn.classList.remove("active"));
      button.classList.add("active");
      cargarMenu(categoria, menuData);
    });
    buttonContainer.appendChild(button);
  }
}

function cargarMenu(categoria, menuData) {
  const menuContainer = document.getElementById("menu-container");
  menuContainer.innerHTML = ""; // Limpiar el contenedor

  if (categoria && menuData[categoria]) {
    const items = menuData[categoria];
    const categoriaElement = document.createElement("div");
    categoriaElement.innerHTML = `
            <h2 class="mt-4 mb-3">${categoria}</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4" id="${categoria
              .replace(/\s+/g, "-")
              .toLowerCase()}-container"></div>
        `;
    menuContainer.appendChild(categoriaElement);

    const categoryContainer = document.getElementById(
      `${categoria.replace(/\s+/g, "-").toLowerCase()}-container`
    );
    items.forEach((item) => {
      const card = document.createElement("div");
      card.className = "col";
      card.innerHTML = `
               <div class="card h-100">
    ${
      item.imagen
        ? `<img src="${item.imagen}" class="card-img-top" alt="${item.nombre}">`
        : ""
    }
    <div class="card-body">
        <h5 class="card-title">${item.nombre}</h5>
        <p class="card-text">${item.descripcion}</p>
        <p class="card-text">${item.tamaño}</p>
        <p class="card-text price">$${item.precio}</p>
    </div>
</div>
            `;
      categoryContainer.appendChild(card);
    });
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  try {
    console.log("empresaid:", empresaid);

    const response = await fetch(
      `./getMenuData.php?empresaid=${empresaid}`
      //   `https://qrsurcba.online/menu/getMenuData.php?empresaid=${empresaid}`
    );
    const menuData = await response.json();
    console.log(menuData);

    window.menuData = menuData;
    // Verificar si hay categorías antes de continuar

    const categorias = Object.keys(menuData);

    if (categorias.length === 0) {
      console.error("No se encontraron categorías en el menú");
      return;
    }
    crearBotonesCategorias(menuData);
    // Verificar si se creó al menos un botón de categoría
    const primerBotonCategoria = document.querySelector(".category-btn");
    if (!primerBotonCategoria) {
      console.error("No se pudo crear el botón de categoría");
      return;
    }
    const primerCategoria = categorias[0];
    cargarMenu(primerCategoria, menuData);
    primerBotonCategoria.classList.add("active");
  } catch (error) {
    console.error("Error al cargar los datos del menú:", error);
  }
});

let mybutton = document.getElementById("btn-back-to-top");

// Cuando el usuario se desplaza 20px desde la parte superior del documento, mostrar el botón
window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// Cuando el usuario hace clic en el botón, desplazarse hasta la parte superior del documento
mybutton.addEventListener("click", backToTop);

function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
