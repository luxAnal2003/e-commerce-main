document.addEventListener("DOMContentLoaded", function() {
    const menuToggle = document.getElementById("menu-toggle");
    const dropdownVertical = document.createElement("div");
    dropdownVertical.classList.add("dropdown-vertical");

    // Clonar los enlaces del dropdown y agregarlos al dropdownVertical
    const dropdownColumns = document.querySelectorAll(".dropdown-column");
    dropdownColumns.forEach(column => {
        const columnClone = column.cloneNode(true);
        const links = columnClone.querySelectorAll('a');
        links.forEach(link => {
            const icon = link.querySelector('i');
            const text = link.textContent.trim();
            link.innerHTML = '';  // Limpiar el contenido del enlace
            link.appendChild(icon);  // Agregar el ícono
            link.innerHTML += '&nbsp;' + text;  // Agregar el texto con un espacio no rompible
        });
        dropdownVertical.appendChild(columnClone);
    });

    document.body.appendChild(dropdownVertical);

    menuToggle.addEventListener("click", function() {
        if (dropdownVertical.style.display === "flex") {
            dropdownVertical.style.display = "none";
        } else {
            dropdownVertical.style.display = "flex";
        }
    });
});
