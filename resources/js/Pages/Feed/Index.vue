<script setup>
import { UserRemoveIcon, UserAddIcon, HashtagIcon } from "@heroicons/vue/solid";
import moment from "moment";
import { onMounted, reactive } from "vue";

const props = defineProps({
    events: Array,
    lobby: Object,
});

const iconMap = {
    lobby_created: {
        icon: HashtagIcon,
        iconBackground: "bg-gray-400",
    },
    member_joined_lobby: {
        icon: UserAddIcon,
        iconBackground: "bg-green-500",
    },
    member_left_lobby: {
        icon: UserRemoveIcon,
        iconBackground: "bg-red-500",
    },
};

const generateContent = (type, body) => {
    let content;

    switch (type) {
        case "lobby_created":
            content = `Lobby ${props.lobby.id} created.`;
            break;
        case "member_joined_lobby":
            content = `${body.name} joined the lobby.`;
            break;
        case "member_left_lobby":
            content = `${body.name} left the lobby.`;
            break;
        default:
            throw new Error("Unrecognised event type: " + type);
    }

    return content;
};

const timeline = reactive(
    props.events.map(({ occurred_at, type, body }) => ({
        content: generateContent(type, body),
        datetime: occurred_at,
        ...iconMap[type],
    }))
);

onMounted(() => {
    Echo.private(`lobby.${props.lobby.id}`)
        .listen(".member_left_lobby", (event) => {
            timeline.push({
                content: generateContent("member_left_lobby", event),
                datetime: event.occurred_at,
                ...iconMap["member_left_lobby"],
            });
        })
        .listen(".member_joined_lobby", (event) => {
            timeline.push({
                content: generateContent("member_joined_lobby", event),
                datetime: event.occurred_at,
                ...iconMap["member_joined_lobby"],
            });
        });
});
</script>

<template>
    <Head title="Feed" />

    <div class="m-6">
        <h1 class="text-2xl font-bold text-gray-900">Activity Feed</h1>
        <p class="text-sm font-light text-gray-500 mb-6">
            See what's been happening in the lobby up until now.
        </p>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        <li v-for="(event, index) in timeline" :key="index">
                            <div class="relative pb-8">
                                <span
                                    v-if="index !== timeline.length - 1"
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
                                            </p>
                                        </div>
                                        <div
                                            class="text-right text-sm whitespace-nowrap text-gray-500"
                                        >
                                            <time :datetime="event.datetime">{{
                                                moment(event.datetime).format(
                                                    "lll"
                                                )
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
    </div>
</template>
