<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import {Head, router, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';
import Bragwall from "@/Partials/Bragwall.vue";


defineProps({
    auth : {
        type: Object,
    }
});

let game = ref({
    name: 'memory',
    wager: 0,
    result: 0,
    status: '',
    timer: 0,
    hash: '',
    ongoing: false,
    completed: false,
    interval: null,
});
function shuffle(array) {
    let currentIndex = array.length;

    // While there remain elements to shuffle...
    while (currentIndex != 0) {

        // Pick a remaining element...
        let randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        // And swap it with the current element.
        [array[currentIndex], array[randomIndex]] = [
            array[randomIndex], array[currentIndex]];
    }
}

let defaultCards = [
    {'id' : 1, 'class': 'test', 'img': 'https://images.squarespace-cdn.com/content/v1/59b2858d8419c20a25b8689e/1583764891824-0BHY7Z4MYZ6H1QV3K5HC/yrgo_ny-e1518639502288.png'},
    {'id' : 1, 'class': 'test', 'img' : 'https://images.squarespace-cdn.com/content/v1/59b2858d8419c20a25b8689e/1583764891824-0BHY7Z4MYZ6H1QV3K5HC/yrgo_ny-e1518639502288.png'},
    {'id' : 2, 'class': 'test', 'img' : 'https://www.logo.wine/a/logo/Laravel/Laravel-Logo.wine.svg'},
    {'id' : 2, 'class': 'test', 'img' : 'https://www.logo.wine/a/logo/Laravel/Laravel-Logo.wine.svg'},
    {'id' : 3, 'class': 'test', 'img' : 'https://cdn.prod.website-files.com/6409b6df2b71f3fef571083d/6412dc65ae08c3b88613bb3f_615dbf243f4ded4697c2d3b7_esgaia_portfolio.png'},
    {'id' : 3, 'class': 'test', 'img' : 'https://cdn.prod.website-files.com/6409b6df2b71f3fef571083d/6412dc65ae08c3b88613bb3f_615dbf243f4ded4697c2d3b7_esgaia_portfolio.png'},
    {'id' : 4, 'class': 'test', 'img' : 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/13/ChatGPT-Logo.png/800px-ChatGPT-Logo.png?20230814075517'},
    {'id' : 4, 'class': 'test', 'img' : 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/13/ChatGPT-Logo.png/800px-ChatGPT-Logo.png?20230814075517'},
    {'id' : 5, 'class': 'test', 'img' : 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/PhpStorm_Icon.svg/512px-PhpStorm_Icon.svg.png?20200803075927'},
    {'id' : 5, 'class': 'test', 'img' : 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/PhpStorm_Icon.svg/512px-PhpStorm_Icon.svg.png?20200803075927'},
    {'id' : 6, 'class': 'test', 'img' : 'https://cdn-icons-png.flaticon.com/512/988/988934.png'},
    {'id' : 6, 'class': 'test', 'img' : 'https://cdn-icons-png.flaticon.com/512/988/988934.png'},
    {'id' : 7, 'class': 'test', 'img' : 'https://static-00.iconduck.com/assets.00/vue-icon-512x442-j09z7tua.png'},
    {'id' : 7, 'class': 'test', 'img' : 'https://static-00.iconduck.com/assets.00/vue-icon-512x442-j09z7tua.png'},
    {'id' : 8, 'class': 'test', 'img' : 'https://d1zviajkun9gxg.cloudfront.net/user/prod/2020/01/03/fastpages-0622317d-e016-4e7a-b25e-8eef9db1610a.png'},
    {'id' : 8, 'class': 'test', 'img' : 'https://d1zviajkun9gxg.cloudfront.net/user/prod/2020/01/03/fastpages-0622317d-e016-4e7a-b25e-8eef9db1610a.png'},
]

let cards= [];
function startGame(){
    game.interval = setInterval(() => {
        game.value.timer++;
    }, 1000);
    router.post('/games/memory', game.value);
    game.value.ongoing = true;
    cards = [...defaultCards];
    shuffle(cards);
    //add cards to the board
    let board = document.getElementById('board');
    board.innerHTML = '';
    cards.forEach((card, index) => {
        let div = document.createElement('div');
        div.className = 'card';
        div.id = index;
        div.innerHTML = `<img src="${card.img}" alt="card">`;
        div.onclick = function() {
            flipCard(div);
        };
        board.appendChild(div);
    });
}

function flipCard(card){
    card.classList.add('flipped');
    let flippedCards = document.querySelectorAll('.flipped');
    if(flippedCards.length === 2){
        //make cards unclickable
        document.querySelectorAll('.card').forEach(card => {
            card.onclick = null;
        });
        setTimeout(() => {
            checkMatch(flippedCards);
            //make cards clickable again
            document.querySelectorAll('.card').forEach(card => {
                card.onclick = function() {
                    flipCard(card);
                };
            });
        }, 1000);
    }
}

function gameDone(){
    clearInterval(game.interval);
    game.value.completed = true;

    game.ongoing = false;
    game.memory = 0;
    game.result = 0;
    game.status = '';
    game.hash = '';
    let stringify = JSON.stringify(game.value);
    //base64 encode hash
    game.value.hash = btoa(stringify);
    router.post('/games/memory/complete', game.value);
    if(game.value.timer < 30) {
        alert('You won! Credits will doubled and added to your account');
    } else {
        alert('You lost! You need to complete the memory in less than 30 seconds to win credits');
    }
}
function checkMatch(flippedCards){
    let firstCard = flippedCards[0];
    let secondCard = flippedCards[1];
    if(firstCard.innerHTML === secondCard.innerHTML){
        firstCard.classList.add('success');
        secondCard.classList.add('success');
        firstCard.classList.remove('flipped');
        secondCard.classList.remove('flipped');
        console.log(document.querySelectorAll('.success').length, cards.length);
        if(document.querySelectorAll('.success').length === cards.length){
            gameDone();
        }
        }else{
            firstCard.classList.remove('flipped');
            secondCard.classList.remove('flipped');
            game.memory = 0;
            game.result = 0;
            game.status = 'You lost!';
        }
}
const submit = () => {
    startGame();
};
const playAgain = () => {
    location.reload();
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xl text-gray-900">Memory</div>
                    <div class="p6">
                        <div v-if="!game.ongoing">
                            <form @submit.prevent="submit">
                                <p v-if="game.status.length > 1">{{game.status}}</p>
                                <label for="credits" class="text-gray-500 text-sm mr-2">Credits</label>
                                <input name="credits" class="mr-3 rounded w-1/6" type="number" min="1" max="10" v-model="game.wager">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Play</button>
                            </form>
                        </div>
                        <div v-else>
                            <p>A game is ongoing. Your bet is {{game.wager}} credits</p>
                            <p>You've played for {{game.timer}} seconds</p>
                        </div>


                        <div id="board">

                        </div>
                        <div v-if="game.completed">
                            <button @click="playAgain" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Play again</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Bragwall/>
    </AuthenticatedLayout>
</template>

<style>
.card {
    width: 23%;
    margin: 1%;
    display: inline-block;
    height: 150px;
    border-radius: 10px;
    border: 1px solid #ccc;
    text-align: center;
    transition: all 0.5s;
    padding: 20px;
}
.card.success {
    border: 1px solid green;
}
.card img {
    display: inline-block;
    margin: 0 auto;
    height: 100%;
    border-radius: 10px;
    transform: rotateY(180deg);
    opacity: 0;
    transition: opacity 1s;
    pointer-events: none;
}
.card:hover {
    background-color: #f0f0f0;
    cursor: pointer;
}

.flipped,
.success {
    transform: rotateY(180deg);
    transition: transform 0.5s;
}
.flipped img,
.success img {
    opacity: 1;
    transition: opacity 1s;
}
</style>

