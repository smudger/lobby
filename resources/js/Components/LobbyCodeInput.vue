<script>
// use normal <script> to declare options
export default {
    inheritAttrs: false,
};
</script>

<script setup>
import { onMounted, ref, watch } from "vue";

const props = defineProps({
    modelValue: String,
    length: Number,
    invalid: Boolean,
    autofocus: { type: Boolean, default: false },
});
const emit = defineEmits(["update:modelValue"]);

const characters = ref([]);

onMounted(() => {
    characters.value = new Array(props.length)
        .fill(undefined)
        .map((_, index) => (props.modelValue || "")[index]);

    if (props.autofocus) document.getElementById("letter-1").focus();
});

watch(
    characters,
    (newCharacters) => {
        emit("update:modelValue", newCharacters.join(""));
    },
    { deep: true }
);

const handleKeydown = (event) => {
    if (event.key.length === 1) {
        event.preventDefault();

        const index = parseInt(event.target.id.slice(-1)) - 1;

        characters.value[index] = event.key;

        if (index === 3) {
            return;
        }

        const letterToMoveTo = `letter-${index + 2}`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
    }

    if (event.key === "Backspace") {
        event.preventDefault();

        const index = parseInt(event.target.id.slice(-1)) - 1;

        characters.value[index] = undefined;

        if (index === 0) {
            return;
        }

        const letterToMoveTo = `letter-${index}`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
    }

    if (event.key === "ArrowLeft") {
        event.preventDefault();

        const index = parseInt(event.target.id.slice(-1)) - 1;

        if (index === 0) {
            return;
        }

        const letterToMoveTo = `letter-${index}`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
    }

    if (event.key === "ArrowRight") {
        event.preventDefault();

        const index = parseInt(event.target.id.slice(-1)) - 1;

        if (index === 3) {
            return;
        }

        const letterToMoveTo = `letter-${index + 2}`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
    }
};

const handlePaste = (event) => {
    characters.value = event.clipboardData
        .getData("text")
        .slice(0, characters.value.length)
        .split("");
};
</script>

<template>
    <div class="bg-white w-80 rounded-md shadow-sm">
        <div class="flex">
            <div
                v-for="index in props.length"
                :key="index"
                class="flex-1 min-w-0"
            >
                <label :for="`letter-${index}`" class="sr-only">
                    Letter {{ index }}
                </label>
                <input
                    :id="`letter-${index}`"
                    :value="characters[index - 1]"
                    type="text"
                    :name="`letter-${index}`"
                    class="caret-transparent uppercase font-mono w-20 text-center text-5xl relative block w-full rounded-none bg-transparent focus:ring-2 focus:z-10"
                    :class="{
                        'rounded-l-md': index === 1,
                        'rounded-r-md': index === props.length,
                        'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 focus:text-indigo-700':
                            !props.invalid,
                        'border-red-500 text-red-600 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                            props.invalid,
                    }"
                    placeholder="X"
                    maxlength="1"
                    v-bind="$attrs"
                    @keydown="handleKeydown"
                    @paste="handlePaste"
                />
            </div>
        </div>
    </div>
</template>
