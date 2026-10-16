async function testAjax(){
    const response = await fetch('/ajax/test');
    const data = await response.json();
    console.log(data);
}

// testAjax();

//deuxième méthode (à l'ancienne):

// fetch('/ajax/test').then((response) => {
//     return response.json().then((data) => {
//         console.log(data);
//     })
// })

let btn = document.querySelector("#btn-ajax-2");
if(btn !== null){
    btn.addEventListener('click', () => {
        testAjax2();
    })
}

async function testAjax2(){
    const response = await fetch('/ajax/test_2');
    const data = await response.json();
    console.log(data);
}


let total = 0;

// async function getCountGenre(){
//     const response = await fetch('/ajax/count_genre');
//     const data = await response.json();
    
//     if(total !== 0 && data.total_genres !== total){
//         alert('Genre supprimé ou ajouté !');
//     }
//     total = data.total_genres;
//     console.log(total);
// }

// getCountGenre();
// setInterval( () => {
//     getCountGenre();
// }, 5000)

let btn2 = document.querySelector("#btn-ajax-3");
if(btn2 !== null){
    btn2.addEventListener('click', () => {
        searchPokemon();
    })
}

async function searchPokemon(){
    const response = await fetch('/ajax/search_pokemon');
    const data = await response.json();
    if(data.isPokemon){
        alert('Le genre Pokemon est arrivé !');
    }else{
        alert('encore un peu de patience...');
    }
}
