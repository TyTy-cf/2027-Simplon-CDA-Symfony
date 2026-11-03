console.log("bonjour");


async function testAjax() {
    const response = await fetch('/ajax/test');
    const data = await response.json();
    console.log(data);
}

testAjax();

// await fetch('/ajax/test')
//     .then((response) => {
//         return response.json().then((data) => {
//             console.log(data);
//         })
//     })

// fetch('/ajax/test_2')
// .then((response) => {
//     return response.json().then((data) => {
//         console.log(data)
//     })
// })

let btn = document.querySelector("#btn-ajax-2");
if(btn !== null) {
    btn.addEventListener('click', () => {
        testAjax2();
    });
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

    if(total !== 0 && data.total_genres !== total) {
        alert('Genre supprimé ou ajouté');
    }

    total = data.total_genres;
    console.log(total);
}

getCountGenre();

setInterval(() => {
    getCountGenre();
}, 5000)

// Créer un bouton sur la home
// au click sur le bouton -> requete ajax vers le serveur
// -> va regarder si un genre possède le name pokemon (LIKE !!)
// -> si oui -> en javascript alert

async function checkPokemonGenre() {
    const response = await fetch('/ajax/check_pokemon_genre');
    const data = await response.json();
    if(data.is_pokemon_exist === true){
        alert('Le genre avec pokemon existe')
    }
}

let btnPokemon = document.querySelector('#btn-pokemon');
if(btnPokemon !== null) {
    btnPokemon.addEventListener('click', () => {
        checkPokemonGenre()
    })
}
