<script setup>
import { UserIcon } from "@heroicons/vue/solid";
import { reactive } from "vue";

const props = defineProps({
    id: String,
});

const timeline = reactive([]);

Echo.channel(`lobby.${props.id}`).listen(".lobby.members.joined", () => {
    timeline.push({
        id: 1,
        content: "Member joined lobby",
        target: `${props.id}`,
        href: `/lobbies/${props.id}`,
        date: "Sep 20",
        datetime: "2020-09-20",
        icon: UserIcon,
        iconBackground: "bg-gray-400",
    });
});
</script>

<template>
    <Head :title="`Lobby ${id}`" />

    <div
        class="flex flex-col items-center justify-center min-h-screen bg-indigo-700"
    >
        <h2
            class="text-3xl font-mono font-extrabold text-white sm:text-4xl mb-6"
        >
            {{ id }}
        </h2>

        <div v-show="timeline.length" class="bg-white p-6 rounded-lg">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <li v-for="(event, eventIdx) in timeline" :key="event.id">
                        <div class="relative pb-8">
                            <span
                                v-if="eventIdx !== timeline.length - 1"
                                class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                aria-hidden="true"
                            />
                            <div class="relative flex space-x-3">
                                <div>
                                    <span
                                        :class="[
                                            event.iconBackground,
                                            'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white',
                                        ]"
                                    >
                                        <component
                                            :is="event.icon"
                                            class="h-5 w-5 text-white"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </div>
                                <div
                                    class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4"
                                >
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            {{ event.content }}
                                            <a
                                                :href="event.href"
                                                class="font-medium text-gray-900"
                                                >{{ event.target }}</a
                                            >
                                        </p>
                                    </div>
                                    <div
                                        class="text-right text-sm whitespace-nowrap text-gray-500"
                                    >
                                        <time :datetime="event.datetime">{{
                                            event.date
                                        }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
