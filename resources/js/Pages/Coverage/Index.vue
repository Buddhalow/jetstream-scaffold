<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Coverages
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <coverage-manager :coverages="coverages"  />
            </div>
        </div>
    </app-layout>
</template>

<script>
import CoverageManager from './CoverageManager'
import AppLayout from './../../Layouts/AppLayout'
import JetSectionBorder from './../../Jetstream/SectionBorder'

export default {
    props: [
        'coverages',
    ],

    components: {
        CoverageManager,
        AppLayout,
        JetSectionBorder,
    },
}
</script>
