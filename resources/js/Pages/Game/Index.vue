<script setup>
defineProps({
    lobby: Object,
    games: Array,
});
</script>

<template>
    <Head title="Game Library" />

    <div class="m-6">
        <h1 class="text-2xl font-bold text-gray-900">Games Library</h1>

        <p class="text-sm font-light text-gray-500 mb-6">
            Browse through the selection of games on offer, and find something
            fun to play.
        </p>

        <div class="bg-gray-200 overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <ul
                    role="list"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <li
                        v-for="game in games"
                        :key="game.slug"
                        class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200"
                    >
                        <div class="flex-1 flex flex-col p-8">
                            <h3 class="text-gray-900 text-sm font-medium">
                                {{ game.name }}
                            </h3>
                            <p class="mt-1 flex-grow text-gray-500 text-sm">
                                {{ game.description }}
                            </p>

                            <Link
                                :href="`/lobbies/${lobby.id}/games/${game.slug}`"
                                class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 sm:w-auto"
                            >
                                Play Now
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
