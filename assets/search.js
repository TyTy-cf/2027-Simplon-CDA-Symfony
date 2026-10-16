// -------- Barre de recherche
let searchBar = document.querySelector('#search-music');
let searchResult = document.querySelector('#search-result');

console.log(searchBar);
console.log(searchResult);

searchBar.addEventListener('input', () => {
    searchMusic();
    console.log(searchBar.value);
});

async function searchMusic(){
    // do something
    if(searchBar.value.trim() !== ''){
        const response = await fetch('/album-search?search=' + searchBar.value);
        console.log(searchBar.value);
        const data = await response.json();

        console.log(data);

        searchResult.textContent = '';
        data.albums.forEach((album) => {
            let div = document.createElement('div');
            div.innerHTML = album.title;

            searchResult.appendChild(div);
        });
    } else {
        searchResult.innerHTML = '';
    }

}