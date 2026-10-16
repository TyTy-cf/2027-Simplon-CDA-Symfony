console.log('bonjour');

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

async function getCountGenre(isFirst) {
    const response = await fetch('/ajax/count_genre');
    const data = await response.json();

    if (total !== 0 && data.total_genres !== total) {
        alert("Genre supprimé ou ajouté !");
    }
    total = data.total_genres;
    console.log(total);

}

getCountGenre(true);

setInterval(() => {
    getCountGenre(false);
}, 5000);


let btnPoke = document.querySelector("#check-pokemon");
if (btnPoke !== null) {
    btnPoke.addEventListener('click', () => {
        getName();
    })
}

async function getName() {
    const response = await fetch('/ajax/name');
    const data = await response.json();
    if (data.is_Poke_exist === true) {
        alert('Ce genre existe !');
    }
}
