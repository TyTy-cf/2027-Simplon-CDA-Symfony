let input = document.querySelector(".form-ghost");
let result = document.querySelector("[data-search-album-content]");
const dataResultSearch = document.createElement("p");
// if (input && result) {
//     input.addEventListener("input", () => {
//         if (input.value !== "") {
//             searchAlbum(input.value);
//         } else {
//             dataResultSearch.innerText = "";
//         }
//     });
// }

if (input && result) {
    input.addEventListener("input", () => {
        searchAlbum(input.value);
    });
}

async function searchAlbumMYVERSION(name) {
    const response = await fetch("/album-search?search=" + name);
    const data = await response.json();
    let dataResult = [];
    data.albums.forEach((album) => {
        dataResult.push(album.title);
    });
    dataResultSearch.innerText = dataResult;
    result.appendChild(dataResultSearch);
}

async function searchAlbum(name) {
    if (input.value.trim() !== "") {
        const response = await fetch("/album-search?search=" + name);
        const data = await response.json();
        result.innerHTML = "";
        data.albums.forEach((album) => {
            let div = document.createElement("div");
            div.innerHTML = album.title;
            result.appendChild(div);
        });
    } else {
        result.innerHTML = "";
    }
}
