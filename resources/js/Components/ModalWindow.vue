<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
            appear
        >
            <div v-if="showing" class="fixed left-0 right-0 top-0 bottom-0 z-10 bg-gray-500/75" @click="close">
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 scale-90"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90"
            appear
        >
            <div
                v-if="showing"
                class="fixed top-0 w-full h-screen flex items-center justify-center z-20"
                @click.self="close">
                <div
                    class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-xl w-full sm:p-6">
                    <div>
                        <slot/>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
export default {
    name: 'ModalWindow',
    props: {
        showing: {
            required: true,
            type: Boolean
        }
    },
    emits: ['close'],
    watch: {
        showing(value) {
            if (value) {
                return document.querySelector('body').classList.add('overflow-hidden');
            }
            document.querySelector('body').classList.remove('overflow-hidden');
        }
    },
    methods: {
        close() {
            this.$emit('close');
        }
    }
};

</script>
