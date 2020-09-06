<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit {{ model }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <jet-form-section @submitted="updateObject">
                    <template #title>
                        Edit {{ model }}
                    </template>

                    <template #description>

                    </template>

                    <template #form>
                        <!-- Object Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <div v-for="field in Object.keys(fields)">
                                <jet-label v-bind:for="field">{{ field }}</jet-label>
                                <jet-input v-if="fields[field].type === 'string'" v-model="updateObjectForm[field]"
                                           v-bind:name="field" v-bind:id="field"/>
                                <jet-input v-if="fields[field].type === 'text'" v-model="updateObjectForm[field]"
                                          v-bind:name="field" v-bind:id="field"></jet-input>
                                <input
                                    v-if="fields[field].selectType === 'datalist' &&fields[field].type === 'relation' && fields[field].relationType === 'belongsTo'"
                                    v-bind:list="field + '_suggestions'" v-bind:id="field + '_query'"
                                    v-bind:name="field + '_query'"
                                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"/>
                                <datalist
                                    v-if="fields[field].selectType === 'datalist' && fields[field].type === 'relation' && fields[field].relationType === 'belongsTo'"
                                    v-bind="updateObjectForm[field]" v-bind:id="field + '_suggestions'"
                                    v-bind:name="field">
                                    <option v-bind:selected="updateObjectForm[field] == obj.id"
                                            v-for="obj in proposals[field]" v-bind:value="obj.id"
                                            v-bind:label="obj.name"></option>
                                </datalist>
                                <div
                                    v-if="fields[field].type === 'relation' && fields[field].relationType === 'belongsTo'"
                                    class="inline-block relative w-64">
                                    <select v-bind="updateObjectForm[field]" v-bind:id="field" v-bind:name="field"
                                            class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                        <option v-bind:selected="!updateObjectForm[field]" value=""
                                                v-bind:label="'Select ' + field"></option>
                                        <option v-bind:selected="updateObjectForm[field] === obj.id"
                                                v-for="obj in proposals[field]" v-bind:value="obj.id"
                                                v-bind:label="obj.name"></option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path
                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <jet-input-error :message="updateObjectForm.error(field)" class="mt-2"/>
                            </div>

                        </div>

                    </template>

                    <template #actions>
                        <jet-action-message :on="updateObjectForm.recentlySuccessful" class="mr-3">
                            Created.
                        </jet-action-message>

                        <jet-button :class="{ 'opacity-25': updateObjectForm.processing }"
                                    :disabled="updateObjectForm.processing">
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
        'model',
        'plural',
        'proposals',
        'objects',
        'fields',
        'id'
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

        updateObject() {
            this.updateObjectForm.put('/user/' + this.model + '/' + this.id, {
                ...Object.keys(this.fields).map(field => ({
                    field,
                    value: ''
                })).reduce((accum, val) => {
                    accum[val.field] = val.value
                    return accum;
                }, {}),
                preserveScroll: true,
                preserveState: true,
            }).then(response => {
            })
        }
    },
    data() {
        return {
            updateObjectForm: this.$inertia.form({
                permissions: []
            }, {
                resetOnSuccess: false,
                bag: 'updateObject',
            }),
        }
    }
}
</script>
