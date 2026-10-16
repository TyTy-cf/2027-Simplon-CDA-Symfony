async function testAjax(){
	const response = await fetch("/ajax/test");
	const data = await response.json();
	console.log(data);
}

//testAjax();

let btn = document.querySelector("#btn-ajax-2");

if(btn !== null){
	btn.addEventListener('click', () => {
		getPoke();
	})
}

async function testAjax2(){
	const response = await fetch("/ajax/test_2");
	const data = await response.json();
	console.log(data);
}


let total = 0;

async function getCountGenre(){
	const response = await fetch("/ajax/count_genre");
	const data = await response.json();
	if (total !== 0 && data.total_genre !== total) {
		alert('genre ajouté ou supprimé!')
	}
	total = data.total_genre;
	console.log(total);
}

//getCountGenre();

// setInterval(() => {
// 	getCountGenre()
// }, 5000);


async function getPoke() {
	const response = await fetch("/ajax/poke");
	const data = await response.json();

	if(data.is_pokemon_exist){
		alert("le genre demander existe")
	} else {
		alert("le genre demander n'existe pas")
	}
	console.log(data);
}


