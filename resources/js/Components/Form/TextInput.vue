<template>
    <input type="text"
           :value="modelValue"
           @input="$emit('update:modelValue', $event.target.value)"
           class="block mt-1.5 w-full px-2.5 py-2 rounded-lg outline-none border border-gray-300 shadow-sm
                hover:border-blue-300
                placeholder:text-gray-500 placeholder:font-normal
                focus:ring-1 focus:ring-blue-500 focus:border-blue-500 ring-inset
                disabled:bg-gray-200 disabled:text-white disabled:cursor-not-allowed disabled:hover:border-gray-300
                duration-150"
           :class="{'ring-1 ring-red-500 border-red-500' : isError} "
    >
</template>

<script>
export default {
    name: "TextInput",
    props: {
        modelValue: {},
        isError: {
            type: Boolean,
            default: false,
        }
    },
    emits: ['update:modelValue'],
}
</script>
