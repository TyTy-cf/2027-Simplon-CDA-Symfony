let dataSearchAlbumContainer = document.querySelector('[data-search-album-container]');
if (dataSearchAlbumContainer !== null) {
    let input = dataSearchAlbumContainer.querySelector('[data-search-album-input]');
    let content = dataSearchAlbumContainer.querySelector('[data-search-album-content]');
    input.addEventListener('input', () => {
        getAlbumData();
    });
}

async function getAlbumData() {
    const response = await fetch('/album_search?search=' + input.value);
    const data = await response.json();
}