// // -------- Barre de recherche
// let searchBar = document.querySelector('#search-music');
// let searchResult = document.querySelector('#search-result');

// console.log(searchBar);
// console.log(searchResult);

// searchBar.addEventListener('input', () => {
//     searchMusic();
//     console.log(searchBar.value);
// });

// async function searchMusic(){
//     // do something
//     if(searchBar.value.trim() !== ''){
//         const response = await fetch('/album-search?search=' + searchBar.value);
//         console.log(searchBar.value);
//         const data = await response.json();

//         console.log(data);

//         searchResult.textContent = '';
//         data.albums.forEach((album) => {
//             let div = document.createElement('div');
//             div.innerHTML = album.title;

//             searchResult.appendChild(div);
//         });
//     } else {
//         searchResult.innerHTML = '';
//     }

// }

const input = document.getElementById("input-ajax");
const results = document.getElementById("results");

input.addEventListener('input', async () => {
    if(input.value.trim() !== ''){
        const response = await fetch('/ajax/search-albums?search=' + input.value);
        const data = await response.json();

        results.innerHTML = '';

        data.albums.forEach(album => {
            const p = document.createElement('p');
            p.textContent = album.title;
            results.appendChild(p);
        });
    } else {
        results.innerHTML = '';
    }
});