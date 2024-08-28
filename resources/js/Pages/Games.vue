<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-xl text-gray-900">Play our games below!</div>
                </div>
            </div>
        </div>

        <div class="py-12 pt-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class=" text-xl text-gray-900">Memory</div>
                    <p class="mb-2">Playing our memory game is both fun and challenging, designed to test and improve your cognitive skills. The objective is simple: match pairs of hidden cards by flipping them over two at a time. The twist? You need to remember the location of each card as you flip them, testing your memory and concentration.</p>
                    <p class="text-gray-500 text-sm mb-5">-- ChatGPT</p>
                    <a class="mt-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/games/memory">Play memory</a>
                </div>
            </div>
        </div>

        <div class="py-12 pt-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class=" text-xl text-gray-900">Coinflip</div>
                    <p class="mb-2">Welcome to our Coin Flip game! It's a simple yet thrilling game of chance where you call heads or tails and flip a virtual coin to see if you’re right. Just choose your side and hit the flip button—if your guess matches the coin's result, you win! It’s quick, easy, and perfect for a quick burst of excitement or settling friendly bets. Try your luck and see if you can come out on top!</p>
                    <p class="text-gray-500 text-sm mb-5">-- ChatGPT</p>
                    <a class="mt-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/games/coinflip">Play coinflip</a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
