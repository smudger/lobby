<script setup>
defineProps({
    code: String,
});
</script>

<template>
    <Head :title="`Lobby ${code}`" />

    <div
        class="flex flex-col items-center justify-center min-h-screen bg-indigo-700"
    >
        <h2 class="text-3xl font-mono font-extrabold text-white sm:text-4xl">
            {{ code }}
        </h2>
    </div>
</template>
