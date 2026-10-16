const input = document.getElementById("input-ajax");
const results = document.getElementById("results");

input.addEventListener('input', async () => {
    if(input.value.trim() !== ''){
        //const response = await fetch('/ajax/search-albums?search=' + input.value);
        const response = await fetch(`/ajax/search-albums?search=${input.value}`);
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