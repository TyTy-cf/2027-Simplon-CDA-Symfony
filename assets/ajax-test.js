console.log('bonjour!!');

async function testAjax() {
    const response = await fetch('/ajax/test');
    const data = await response.json();
    console.log(data);
}

testAjax();

let btn = document.querySelector("#btn-ajax-2");
if (btn !== null) {
    btn.addEventListener('click', () => {
        testAjax2();
    })
}

async function testAjax2() {
    const response = await fetch('/ajax/test_2');
    const data = await response.json();
    console.log(data);
}
let total = 0;

async function getCountGenre() {
    const response = await fetch('/ajax/count_genre');
    const data = await response.json();

    if (total !== 0 && data.total_genres !== total) {
        alert('nouveau genre ajouté !');
    }

    total = data.total_genres;
    console.log(total);
}

getCountGenre();

setInterval(() => {
    getCountGenre();
}, 5000)


let btnPokemon = document.querySelector('#check-pokemon');
console.log(btnPokemon);
if (btnPokemon !== null) {
    btnPokemon.addEventListener('click', () => {
        checkPokemonGenre();
    })
}

async function checkPokemonGenre() {
    const response = await fetch('/ajax/check_pokemon_genre');
    const data = await response.json();
    if (data.is_pokemon_exist === true) {
        alert('Le genre pokemon existe déjà en base de données !!');
    }
}
checkPokemonGenre();