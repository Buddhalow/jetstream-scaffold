<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div v-if="objects.length > 0">
                    <jet-section-border/>

                    <!-- Manage Objects -->
                    <div class="mt-10 sm:mt-0">
                        <jet-action-section>
                            <template #title>
                                Manage Objects
                            </template>

                            <template #description>
                                You may delete any of your existing objects if they are no longer needed.
                            </template>

                            <!-- Object List -->
                            <template #content>
                                <div class="space-y-6">
                                    <div class="flex items-center justify-between" v-for="object in objects">
                                        <div>
                                            {{ object.name }}
                                        </div>

                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-400" v-if="object.created_at">
                                                Last used {{ fromNow(object.created_at) }}
                                            </div>

                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                    @click="confirmObjectDeletion(object)">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </jet-action-section>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ObjectManager from './ObjectManager'
import AppLayout from './../../Layouts/AppLayout'
import JetSectionBorder from './../../Jetstream/SectionBorder'

export default {
    props: [
        'objects',
    ],

    components: {
        AppLayout,
        JetSectionBorder,
    },
    methods: {
        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },
    }
}
</script>
