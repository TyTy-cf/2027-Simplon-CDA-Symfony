import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import './ajax-test.js';
import './search-test.js';


let button = document.getElementById("buttonFav");
let buttonListen = document.getElementById("buttonList");
let id;
if(button){
    id = button.dataset.trackId;
    checkFav();

button.addEventListener("click", (e)=>{
    fav();
});
}

if(buttonListen){
    id = buttonListen.dataset.trackId;
    buttonListen.addEventListener("click", ()=>{
        listen();
    })
}

async function listen(){
    try {
        const res = await fetch(`/history/${id}`);
        if (!res.ok){
            throw new Error(res.status);
        }
        const result = await res.json();
        result.listened && (buttonListen.innerText = "Listening ...");
        setTimeout(()=>{buttonListen.innerText = "Listen";},buttonListen.dataset.trackDur*1000)
    } catch (error){
        console.error(error.message);
    }
}

async function checkFav(){
    try {
        const res = await fetch(`/favoriteCheck/${id}`);
        if (!res.ok){
            throw new Error(res.status);
        }
        const result = await res.json();
        result.fav?button.innerText = "Unfav":button.innerText = "fav"
    } catch (error){
        console.error(error.message);
    }
}

async function fav(){
    try {
        const res = await fetch(`/favorite/${id}`);
        if (!res.ok){
            throw new Error(res.status);
        }
        const result = await res.json();
        result.fav?button.innerText = "Unfav":button.innerText = "fav"
    } catch (error){
        console.error(error.message);
    }
}

