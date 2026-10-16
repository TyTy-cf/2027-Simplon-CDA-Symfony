let search = document.querySelector("#search");
let content = document.querySelector("#search-result");


if (search !== null) {
	search.addEventListener('input', () =>{
		searchAlbum();
	})
}

async function searchAlbum() {
	if (search.value.trim() !== "") {
		const response = await fetch('/albumSearch?search='+ search.value);
		const data = await response.json();

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
