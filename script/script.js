const menuData = {
    "Café": [
        {"nombre": "Ristretto", "descripcion": "25ml", "precio": 2500, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Espresso", "descripcion": "40ml", "precio": 2.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Lungo", "descripcion": "110ml", "precio": 3.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Latte", "descripcion": "110ml", "precio": 3.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Lágrima", "descripcion": "", "precio": 3.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Cortado", "descripcion": "", "precio": 3.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Café con leche", "descripcion": "", "precio": 3.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Café Benita", "descripcion": "", "precio": 4.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Café Helado", "descripcion": "", "precio": 4.00, "imagen": "/api/placeholder/300/200"}
    ],
    "Sin Café": [
        {"nombre": "Submarino", "descripcion": "", "precio": 4.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Chocolatada", "descripcion": "", "precio": 4.00, "imagen": "/api/placeholder/300/200"}
    ],
    "Té": [
        {"nombre": "Té", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Té con leche", "descripcion": "", "precio": 2.50, "imagen": "/api/placeholder/300/200"}
    ],
    "Alfajores": [
        {"nombre": "Blanco / Negro", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Conito Dulce de Leche", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Galletas bañadas en chocolate", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Galletas con Arándanos", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Galletas con Chips de Chocolate", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Bombon Frutal", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Estuche x 2 alfajores", "descripcion": "", "precio": 3.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Estuche Triologia 6 alfajores", "descripcion": "", "precio": 9.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Cordobés", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "de Cacao", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "de Coco", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "de Merengue", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "de Maicena", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Conito de Dulce de Leche", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Colacion", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Nuez Confitada", "descripcion": "", "precio": 2.25, "imagen": "/api/placeholder/300/200"}
    ],
    "Dulces Caseros": [
        {"nombre": "Cookies vainilla con chips", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Cookies chocolate con nuez", "descripcion": "", "precio": 2.25, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Palmeritas", "descripcion": "", "precio": 1.75, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Alfajorcitos de Maicena", "descripcion": "", "precio": 2.00, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Medialunas", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"},
        {"nombre": "Criollos", "descripcion": "", "precio": 1.50, "imagen": "/api/placeholder/300/200"}
    ]
};

function crearBotonesCategorias() {
    const buttonContainer = document.getElementById('category-buttons');
    for (const categoria in menuData) {
        const button = document.createElement('button');
        button.textContent = categoria;
        button.className = 'btn category-btn';
        button.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            cargarMenu(categoria);
        });
        buttonContainer.appendChild(button);
    }
}

function cargarMenu(categoria) {
    const menuContainer = document.getElementById('menu-container');
    menuContainer.innerHTML = ''; // Limpiar el contenedor

    if (categoria && menuData[categoria]) {
        const items = menuData[categoria];
        const categoriaElement = document.createElement('div');
        categoriaElement.innerHTML = `
            <h2 class="mt-4 mb-3">${categoria}</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4" id="${categoria.replace(/\s+/g, '-').toLowerCase()}-container"></div>
        `;
        menuContainer.appendChild(categoriaElement);

        const categoryContainer = document.getElementById(`${categoria.replace(/\s+/g, '-').toLowerCase()}-container`);
        items.forEach(item => {
            const card = document.createElement('div');
            card.className = 'col';
            card.innerHTML = `
                <div class="card h-100">
                    <img src="${item.imagen}" class="card-img-top" alt="${item.nombre}">
                    <div class="card-body">
                        <h5 class="card-title">${item.nombre}</h5>
                        <p class="card-text">${item.descripcion}</p>
                        <p class="card-text price">$${item.precio}</p>
                    </div>
                </div>
            `;
            categoryContainer.appendChild(card);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    crearBotonesCategorias();
    // Cargar la primera categoría por defecto
    const primerCategoria = Object.keys(menuData)[0];
    cargarMenu(primerCategoria);
    document.querySelector('.category-btn').classList.add('active');
});

let mybutton = document.getElementById("btn-back-to-top");

// Cuando el usuario se desplaza 20px desde la parte superior del documento, mostrar el botón
window.onscroll = function() {scrollFunction()};

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