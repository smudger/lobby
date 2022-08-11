<script setup>
import { ref } from "vue";

const letter1 = ref("A");
const letter2 = ref("B");
const letter3 = ref("C");
const letter4 = ref("D");

const handleKeydown = (event) => {
    // alternatively adopt something like vim replace mode, i.e. no caret characters type in current box, arrows backspace etc move between boxes
    // leftward moving keys at selectionStart 0 need to move first into previous input at selectionStart/End 1
    //      Backspace, ArrowLeft
    // rightward moving keys at selectionStart 1 need to move first into next input
    //      Characters, Tab, ArrowRight
    if (event.key === "Backspace" || event.key === "ArrowLeft") {
        if (event.target.id === "letter-1") {
            return;
        }

        const target = document.getElementById(event.target.id);
        if (target.selectionStart !== 0) {
            return;
        }

        if (target.selectionStart !== target.selectionEnd) {
            return;
        }

        const letterToMoveTo = `letter-${
            parseInt(event.target.id.slice(-1)) - 1
        }`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
        elementToMoveTo.selectionStart = elementToMoveTo.selectionEnd = 1;
    }

    if (event.key === "ArrowRight" || event.key.length === 1) {
        if (event.target.id === "letter-4") {
            return;
        }

        const target = document.getElementById(event.target.id);
        if (target.selectionStart === 0) {
            return;
        }

        if (target.selectionStart !== target.selectionEnd) {
            return;
        }

        const letterToMoveTo = `letter-${
            parseInt(event.target.id.slice(-1)) + 1
        }`;
        const elementToMoveTo = document.getElementById(letterToMoveTo);
        elementToMoveTo.focus();
    }
};
const handlePaste = (event) => {
    console.log(event.clipboardData.getData("text"));
};
</script>

<template>
    <div class="flex flex-col justify-center min-h-screen bg-indigo-700">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <div class="mt-10 bg-white rounded-md shadow-sm">
                <div class="flex">
                    <div class="w-1/4 flex-1 min-w-0">
                        <label for="letter-1" class="sr-only">Letter 1</label>
                        <input
                            id="letter-1"
                            :value="letter1"
                            type="text"
                            name="letter-1"
                            class="uppercase font-mono w-24 text-center text-7xl focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none rounded-l-md bg-transparent focus:z-10 border-gray-300"
                            placeholder="X"
                            maxlength="1"
                            @keydown="handleKeydown"
                            @paste="handlePaste"
                        />
                    </div>
                    <div class="w-1/4 flex-1 min-w-0">
                        <label for="letter-2" class="sr-only">Letter 2</label>
                        <input
                            id="letter-2"
                            :value="letter2"
                            type="text"
                            name="letter-2"
                            class="uppercase font-mono w-24 text-center text-7xl focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none bg-transparent focus:z-10 border-gray-300"
                            placeholder="X"
                            maxlength="1"
                            @keydown="handleKeydown"
                            @paste="handlePaste"
                        />
                    </div>
                    <div class="w-1/4 flex-1 min-w-0">
                        <label for="letter-3" class="sr-only">Letter 3</label>
                        <input
                            id="letter-3"
                            :value="letter3"
                            type="text"
                            name="letter-3"
                            class="uppercase font-mono w-24 text-center text-7xl focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none bg-transparent focus:z-10 border-gray-300"
                            placeholder="X"
                            maxlength="1"
                            @keydown="handleKeydown"
                            @paste="handlePaste"
                        />
                    </div>
                    <div class="w-1/4 flex-1 min-w-0">
                        <label for="letter-4" class="sr-only">Letter 4</label>
                        <input
                            id="letter-4"
                            :value="letter4"
                            type="text"
                            name="letter-4"
                            class="uppercase font-mono w-24 text-center text-7xl focus:ring-indigo-500 focus:border-indigo-500 relative block w-full rounded-none rounded-r-md bg-transparent focus:z-10 border-gray-300"
                            placeholder="X"
                            maxlength="1"
                            @keydown="handleKeydown"
                            @paste="handlePaste"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
