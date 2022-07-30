<template>
    <div>
        <label class="block text-sm text-gray-500" :for="this.for">
            {{ label }}
        </label>
        <slot/>
        <div class="h-4">
            <span class="text-red-500 text-sm" v-if="error">
                {{ error }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "FormGroup",
    props: {
        error: {

        },
        label: {
            type: String,
        },
        for: {
            type: String,
        },
    },
}
</script>
