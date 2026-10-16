// ========= Imports =========

// ========= Logic =========
console.log("hello from ajax.js");

async function testAjax() {
    const response = await fetch('/ajax/test');
    const data = await response.json();

    console.log(data);
};

testAjax();

let btn = document.querySelector('#btn-test-ajax-2');
if(btn !== null){
    btn.addEventListener('click', () => {
        testAjax2();
    });
}

async function testAjax2() {
    const response = await fetch('/ajax/test_2');
    const data = await response.json();

    console.log(data);
}
// ---------------------------------------
let total = 0;

async function getCountGenre() {
    const response = await fetch('/ajax/count_genre');
    const data = await response.json();

    if(total !== 0 && data.total_genres !== total){
        alert('Nouveau genre ajouté');
    }

    total = data.total_genres;

    console.log(total);
}

getCountGenre();

// setInterval(() => {
//     getCountGenre();
// }, 5000);
// ------------------------------------
let btnPokemon = document.querySelector('#btn-pokemon');

if(btnPokemon !== null){
    btnPokemon.addEventListener('click', () => {
        findPokemon();
    });
}

async function findPokemon() {
    const response = await fetch("/ajax/pokemon");
    const data = await response.json();
    if(data.is_pokemon_exist === true){
        alert("pokemon");
    }
    console.log(data);
}