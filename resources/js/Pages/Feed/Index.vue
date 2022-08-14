<script setup>
import { UserRemoveIcon, UserAddIcon, HashtagIcon } from "@heroicons/vue/solid";
import moment from "moment";

const timeline = [
    {
        id: 1,
        content: "Created lobby",
        target: "AAAA",
        href: "#",
        date: "Sep 20",
        datetime: "2020-09-20",
        icon: HashtagIcon,
        iconBackground: "bg-gray-400",
    },
    {
        id: 2,
        content: "Member joined",
        target: "Bethany Blake",
        href: "#",
        date: "Sep 22",
        datetime: "2020-09-22",
        icon: UserAddIcon,
        iconBackground: "bg-green-500",
    },
    {
        id: 3,
        content: "Member left",
        target: "Bethany Blake",
        href: "#",
        date: "Sep 28",
        datetime: "2020-09-28",
        icon: UserRemoveIcon,
        iconBackground: "bg-red-500",
    },
    {
        id: 4,
        content: "Member joined",
        target: "Ketherine Snyder",
        href: "#",
        date: "Sep 30",
        datetime: "2020-09-30",
        icon: UserAddIcon,
        iconBackground: "bg-green-500",
    },
    {
        id: 5,
        content: "Member left",
        target: "Katherine Snyder",
        href: "#",
        date: "Oct 4",
        datetime: "2020-10-04",
        icon: UserRemoveIcon,
        iconBackground: "bg-red-500",
    },
];
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
                        <li
                            v-for="(event, eventIdx) in timeline"
                            :key="event.id"
                        >
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
                                                moment()
                                                    .subtract(
                                                        8 - event.id,
                                                        "minutes"
                                                    )
                                                    .fromNow()
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
