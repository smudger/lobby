<script setup>
defineProps({
    lobby: Object,
    game: Object,
});
</script>

<template>
    <Head :title="game.name" />

    <p>{{ game.description }}</p>
</template>
