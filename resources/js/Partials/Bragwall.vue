<script setup>
import { onMounted, ref } from 'vue';
import { Link, useForm, router, usePage } from '@inertiajs/vue3';
import TextArea from "@/Components/TextArea.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

let bragwall = ref([]);

const user = usePage().props.auth.user;
//on mounted fetch from API
onMounted(() => {
    fetch('/api/bragwall')
        .then(response => response.json())
        .then(data => {
            bragwall.value = data;
        });
})

</script>

<template>
    <div class="py-12 pt-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class=" text-xl text-gray-900">Brag wall</div>
                <p class="mb-2"></p>
                <div v-for="item in bragwall" :key="item.id">
                    <p class="text-sm text-gray-500">{{ item.user.name }} won {{item.winnings}} credits in {{item.game}} and says  "<span v-html="item.user.winning_message"></span>"</p>
                </div>
            </div>
        </div>
    </div>
</template>
