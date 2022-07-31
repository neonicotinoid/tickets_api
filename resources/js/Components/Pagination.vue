<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap space-x-1 -mb-1 mt-4">
            <div v-for="(link, index) in links">
                <div v-if="link.active" class="bg-white border-blue-500 rounded text-blue-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" v-html="link.label">

                </div>
                <InertiaLink v-else :href="link.url" class="bg-white border-gray-300 rounded text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" v-html="link.label"/>
            </div>
        </div>
    </div>
</template>

<script>
import {InertiaLink} from "@inertiajs/inertia-vue3";

export default {
    props: {
        links: Array,
    },
    components : {
        InertiaLink
    }
}
</script>
