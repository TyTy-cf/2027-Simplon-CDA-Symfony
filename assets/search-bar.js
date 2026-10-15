let inputSearchBar = document.querySelector('#input-search-bar');
let inputSearchForm = document.querySelector('#input-search-form');
let dataSearchAlbumContainer = document.querySelector('[data-search-album-container]')
if(inputSearchBar !== null) {
    inputSearchBar.addEventListener('input', () => {
        // inputSearchForm.submit();
        searchAlbum();
    })
}

async function searchAlbum() {
    const response = await fetch(`/ajax/search-album?search=${inputSearchBar.value}`);
    const data = await response.json();
    console.log(data);
}
