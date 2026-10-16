async function testAjax() {
    const response = await fetch("/ajax/test");
    const data = await response.json();
    console.log(data);
}

async function testAjax2() {
    const response = await fetch("/ajax/test_2");
    const data = await response.json();
    console.log(data);
}

async function poke() {
    const response = await fetch("/ajax/pokemon");
    const data = await response.json();
    if (data.isPoke) {
        alert("Genre poke exist");
    }
}
let total = 0;

async function getCountGenre() {
    const response = await fetch("/ajax/count_genre");
    const data = await response.json();
    if (total !== 0 && data.total_genres !== total) {
        alert("nombre de genre modifié !");
    }
    total = data.total_genres;
}

let btn = document.querySelector("#btn-ajax-2");
let btnPoke = document.querySelector("#btn-ajax-poke");
if (btn !== null) {
    btn.addEventListener("click", () => {
        testAjax2();
    });
}
if (btnPoke !== null) {
    btnPoke.addEventListener("click", () => {
        poke();
    });
}
testAjax();

getCountGenre();

setInterval(() => {
    getCountGenre();
}, 5000);
