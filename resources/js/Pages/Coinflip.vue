<script setup>
import { ref, onUnmounted } from 'vue';
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Bragwall from "@/Partials/Bragwall.vue";

defineProps({
    auth: {
        type: Object,
    }
});

const user = usePage().props.auth.user;

const game = ref({
    name: 'coinflip',
    wager: 1,
    result: '',
    status: '',
    coinpick: '',
    ongoing: false,
    completed: false,
});

const isFlipping = ref(false);
const showResult = ref(false);
const form = useForm({
    wager: 1,
});

function startGame() {

    fetch('/games/coinflip', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(game.value),
    })
    .then(response => response.json())
    .then(data => {
        if(data.error) {
            alert(data.error);
        } else {
            if (form.wager < 1 || form.wager > 100) {
                game.value.status = 'Wager must be between 1 and 10 credits.';
                return;
            }
            user.credits -= form.wager;
            game.value.wager = form.wager;
            game.value.ongoing = true;
            game.value.completed = false;
            game.value.status = 'Flipping the coin...';
            flipCoin();
        }
    });

}

function flipCoin() {
    isFlipping.value = true;
    showResult.value = false;
    game.value.result = ''; // Clear previous result

    // Randomly determine the result
    const random = Math.random();
    const result = random < 0.5 ? 'heads' : 'tails';

    // Simulate the coin flip animation
    setTimeout(() => {
        game.value.result = result;
        if(game.value.coinpick === result) {
            user.credits += game.value.wager * 2;
            game.value.status = `You won ${game.value.wager} credits!`;
        } else {
            game.value.status = `You lost ${game.value.wager} credits!`;
        }
        game.value.ongoing = false;
        game.value.completed = true;
        game.value.status = `It's ${result}!`;
        isFlipping.value = false;
        showResult.value = true;
        gameDone();
    }, 2700); // 3 seconds for the animation

}

function gameDone(){

    game.ongoing = false;
    game.hash = '';
    let stringify = JSON.stringify(game.value);
    //base64 encode hash
    game.value.hash = btoa(stringify);
    router.post('/games/coinflip/complete', game.value);
}

function playAgain() {
    game.value = {
        name: 'coinflip',
        wager: 1,
        result: '',
        status: '',
        ongoing: false,
        completed: false,
    };
    form.wager = 1;
    showResult.value = false;
}

// Clean up any ongoing processes when the component is unmounted
onUnmounted(() => {
    // Add any cleanup code here if needed
});
</script>

<template>
    <Head title="Coinflip" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Coinflip</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xl text-gray-900 mb-5">Coinflip</div>
                    <div class="p-6">
                        <div class="mb-5">
                            <div class="coin" :class="{
                                'flipping': isFlipping,
                                'heads': showResult && game.result === 'heads',
                                'tails': showResult && game.result === 'tails'
                            }">
                                <div class="coin-side front">
                                    <span>Heads</span>
                                </div>
                                <div class="coin-side back">
                                    <span>Tails</span>
                                </div>
                            </div>
                        </div>
                        <div v-if="!game.ongoing && !game.completed">
                            <form @submit.prevent="startGame">
                                <label for="wager" class="text-gray-500 text-sm mr-2">Credits</label>
                                <input id="wager" v-model="form.wager" class="mr-3 rounded w-1/6" type="number" min="1" max="100" required>
                                <input type="radio" id="heads" name="coin" value="heads" v-model="game.coinpick" required>
                                <label class="mr-5 ml-1" for="heads">Heads</label>
                                <input type="radio" id="tails" name="coin" value="tails" v-model="game.coinpick" required>
                                <label class="mr-5 ml-1" for="tails">Tails</label>

                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" :disabled="game.ongoing">
                                    Flip
                                </button>
                            </form>
                        </div>
                        <div v-if="game.ongoing">
                            <p>Flipping the coin... Your bet is {{ game.wager }} credits on {{game.coinpick}}</p>
                        </div>

                        <div v-if="game.completed" class="mt-4">
                            <p class="mb-2">{{ game.status }}</p>
                            <a href="/games/coinflip" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Play again
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Bragwall />
    </AuthenticatedLayout>
</template>

<style scoped>
.coin {
    width: 150px;
    height: 150px;
    position: relative;
    margin: 0 auto;
    transform-style: preserve-3d;
    transition: transform 0.1s;
}

.coin.flipping {
    animation: flipCoin 3s ease-out forwards;
}

.coin-side {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    backface-visibility: hidden;
}

.front {
    border: 3px solid #918437;
    background-color: gold;
    z-index: 2;
}

.back {
    border: 3px solid #9a9898;
    background-color: silver;
    transform: rotateY(180deg);
}

.coin.heads {
    transform: rotateY(0deg);
}

.coin.tails {
    transform: rotateY(180deg);
}

@keyframes flipCoin {
    0% {
        transform: rotateY(0deg) scale(1);
    }
    50% {
        transform: rotateY(1260deg) scale(1.3); /* Flip halfway and enlarge */
    }
    100% {
        transform: rotateY(2520deg) scale(1); /* Complete 5 spins */
    }
}
</style>
