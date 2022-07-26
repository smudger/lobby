<script setup>
import moment from "moment";
import { onMounted, onUnmounted, ref } from "vue";

let interval = null;

const props = defineProps({
    lobby: Object,
    me: Object,
});

const addAgo = (members) => {
    return members.map((member) => ({
        ...member,
        ago: moment(member.joined_at).fromNow(),
    }));
};

const members = ref(addAgo(props.lobby.members));

onUnmounted(() => {
    clearInterval(interval);
});

onMounted(() => {
    interval = setInterval(() => {
        members.value = addAgo(members.value);
    }, 10000);

    Echo.private(`lobby.${props.lobby.id}`)
        .listen(".member_left_lobby", ({ id }) => {
            members.value = members.value.filter((member) => member.id !== id);
        })
        .listen(".member_joined_lobby", (member) => {
            members.value = members.value.concat(addAgo([member]));
        });
});
</script>

<template>
    <Head title="Members" />

    <div class="m-6">
        <h1 class="text-2xl font-bold text-gray-900">Members</h1>
        <p class="text-sm font-light text-gray-500 mb-6">
            Manage the members within the lobby, and keep those troublemakers
            out.
        </p>

        <div class="bg-white shadow overflow-hidden rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
                <li
                    v-for="(member, index) in members"
                    :key="index"
                    class="py-4 px-3 flex justify-between items-center"
                >
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ member.name }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            Joined
                            {{ member.ago }}.
                        </p>
                    </div>
                    <div>
                        <Link
                            v-if="member.id !== me.member_id"
                            :href="`/lobbies/${lobby.id}/members/${member.id}`"
                            method="delete"
                            as="button"
                            type="button"
                            class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Remove
                        </Link>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
