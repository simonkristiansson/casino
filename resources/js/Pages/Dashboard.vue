<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import {ref} from "vue";
import Bragwall from "@/Partials/Bragwall.vue";

let gift = ref({
    user: null,
    amount: null,
});
const submit = () => {
    router.post('/games/gift', gift.value);
};
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Home</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-xl text-gray-900 mb-5">Go play our games and spend your credits!</p>

                        <a class="mt-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/games">Go to games</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="py-12 pt-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class=" text-xl text-gray-900">Gift coins</div>
                    <p class="mb-2"></p>
                    <form @submit.prevent="submit">
                        <label for="credits" class="text-gray-500 text-sm mr-2">Credits</label>
                        <input name="credits" class="mr-3 rounded w-1/6" type="text" min="1" max="1000" v-model="gift.amount">
                        <label for="credits" class="text-gray-500 text-sm mr-2">User ID</label>
                        <input name="credits" class="mr-3 rounded w-1/6" type="text" v-model="gift.user">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Gift</button>
                    </form>
                </div>
            </div>
        </div>

        <Bragwall/>
    </AuthenticatedLayout>
</template>
