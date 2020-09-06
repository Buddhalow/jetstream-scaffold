<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Exposures
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <exposure-manager :exposures="exposures"
                                   :available-permissions="availablePermissions"
                                   :default-permissions="defaultPermissions" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import ExposureManager from './ExposureManager'
import AppLayout from './../../Layouts/AppLayout'
import JetSectionBorder from './../../Jetstream/SectionBorder'

export default {
    props: [
        'exposures',
        'availablePermissions',
        'defaultPermissions',
    ],

    components: {
        ExposureManager,
        AppLayout,
        JetSectionBorder,
    },
}
</script>
