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
                                <div class="space-y-12">
                                    <table class="table-fixed" style="width: 100%" v-if="view === 'table'">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2" v-for="field of Object.keys(fields)">
                                                    {{field}}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="object in objects">
                                                <td class="border px-4 py-2" v-for="field of Object.keys(fields)">
                                                    {{object[field]}}
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <a
                                                        class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                                        :href="'/dashboard/' + plural + '/' + object.id"
                                                    >
                                                        Edit
                                                    </a>
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <button
                                                        class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                                        @click="confirmObjectDeletion(object)"
                                                    >
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="view === 'list'" class="flex items-center justify-between" v-for="object of objects">
                                        <div>
                                            {{ object.name }}
                                        </div>

                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-400" v-if="object.created_at">
                                                Last used {{ fromNow(object.created_at) }}
                                            </div>

                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" @click="confirmObjectDeletion(object)">
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
import JetActionSection from './../../Jetstream/ActionSection'


export default {
    props: [
        'objects',
        'model',
        'fields',
        'plural'
    ],

    components: {
        AppLayout,
        JetSectionBorder,
        JetActionSection
    },
    data() {
        return {
            view: 'table'
        }
    },
    methods: {
        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },
    }
}
</script>
