function showCategory(goodCategory) {
    // Now we need to hide all tr's with wrong class
    Object.values(document.getElementsByTagName("tr")).forEach(row => {
        if (row.className == goodCategory || row.className == "") {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

document.getElementById("category").addEventListener('change', (e) => {
    let goodCategory = e.target.value;
    showCategory(goodCategory);
});

// When the script first loads we need to show only the first category, because it won't be filtered yet
showCategory(document.getElementById("category").value);
