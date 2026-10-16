let searchInput = document.querySelector('#input-recherche');
let container = document.querySelector('#search-container');
let content = document.querySelector('#search-content');

if (searchInput !== null){
    searchInput.addEventListener('input', () => {
        filterAlbum();
    })
}

async function filterAlbum(){
     if(searchInput.value.trim() !== ""){
        const response = await fetch('/album-search?search=' + searchInput.value);
    const data = await response.json();
    console.log(data.albums);
    
    content.innerHTML = '';
            data.albums.forEach((album) => {
                let div = document.createElement('div');
                div.innerHTML = album.title;

                content.appendChild(div);
            })
        } else {
            content.innerHTML = '';
     }
    
}
