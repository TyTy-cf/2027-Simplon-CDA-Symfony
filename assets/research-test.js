const input = document.getElementById('search-album');
console.log(input);
const results = document.getElementById('search-results');

input.addEventListener('input', function () {
    fetch('/ajax/search-album?search=' + input.value)
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            console.log(data);

            results.innerHTML = '';
            for (const album of data.albums) {
                results.innerHTML += '<div>' + album.title + '</div>';
            }
        });
});