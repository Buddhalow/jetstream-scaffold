<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="createObject">
                    <template #title>
                        Create Object
                    </template>

                    <template #description>
                        Object
                    </template>

                    <template #form>
                        <!-- Object Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <div v-for="field in Object.keys(fields)">
                                <jet-label v-bind:for="field" v-model="fields[field].id"/>
                                <jet-input v-model="createObjectForm[field]" v-bind:name="field" v-bind:id="field"/>
                                <jet-input-error :message="createObjectForm.error(field)" class="mt-2"/>
                            </div>

                        </div>

                    </template>

                    <template #actions>
                        <jet-action-message :on="createObjectForm.recentlySuccessful" class="mr-3">
                            Created.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': createObjectForm.processing }"
                                    :disabled="createObjectForm.processing">
                            Create
                        </jet-button>
                    </template>
                </jet-form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ObjectManager from './ObjectManager'
import AppLayout from './../../Layouts/AppLayout'
import JetSectionBorder from './../../Jetstream/SectionBorder'
import JetActionMessage from "../../Jetstream/ActionMessage";
import JetActionSection from "../../Jetstream/ActionSection";
import JetButton from "../../Jetstream/Button";
import JetConfirmationModal from "../../Jetstream/ConfirmationModal";
import JetDangerButton from "../../Jetstream/DangerButton";
import JetDialogModal from "../../Jetstream/DialogModal";
import JetFormSection from "../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";

export default {
    props: [
        'objects',
        'fields'
    ],

    components: {
        JetActionMessage,
        JetActionSection,
        JetButton,
        JetConfirmationModal,
        JetDangerButton,
        JetDialogModal,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        ObjectManager,
        AppLayout,
        JetSectionBorder,
    },
    methods: {
        createObjectForm: this.$inertia.form(
            Object.keys(this.fields).map(field => ({
                field,
                value: ''
            })).reduce((accum, val) => {
                accum[val.field] = val.value
                return accum;
            }, {}), {
                bag: 'createObject',
                resetOnSuccess: true,
            }),
        createObject() {
            this.createObjectForm.post('/user/' + this.model, Object.keys(this.fields).map(field => ({
                field,
                value: ''
            })).reduce((accum, val) => {
                accum[val.field] = val.value
                return accum;
            }, {})).then(response => {
                if (!this.createObjectForm.hasErrors()) {

                }
            })
        },
    }
}
</script>
