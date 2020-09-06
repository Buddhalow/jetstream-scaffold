<template>
    <div>
        <!-- Generate Object -->
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
                        <jet-input v-model="createObjectForm[field]" v-bind:name="field" v-bind:id="field" />
                        <jet-input-error :message="createObjectForm.error('name')" class="mt-2"/>
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


        <!-- Object Value Modal -->
        <jet-dialog-modal :show="displayingObject" @close="displayingObject = false">
            <template #title>
                Object
            </template>

            <template #content>
                <div>
                    Please copy your new Object. For your security, it won't be shown again.
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500"
                     v-if="$page.jetstream.flash.object">
                    {{ $page.jetstream.flash.object }}
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="displayingObject = false">
                    Close
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>


        <!-- Delete Object Confirmation Modal -->
        <jet-confirmation-modal :show="objectBeingDeleted" @close="objectBeingDeleted = null">
            <template #title>
                Delete Object
            </template>

            <template #content>
                Are you sure you would like to delete this Object?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="objectBeingDeleted = null">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteObject"
                                   :class="{ 'opacity-25': deleteObjectForm.processing }"
                                   :disabled="deleteObjectForm.processing">
                    Delete
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
import JetActionMessage from './../../Jetstream/ActionMessage'
import JetActionSection from './../../Jetstream/ActionSection'
import JetButton from './../../Jetstream/Button'
import JetConfirmationModal from './../../Jetstream/ConfirmationModal'
import JetDangerButton from './../../Jetstream/DangerButton'
import JetDialogModal from './../../Jetstream/DialogModal'
import JetFormSection from './../../Jetstream/FormSection'
import JetInput from './../../Jetstream/Input'
import JetInputError from './../../Jetstream/InputError'
import JetLabel from './../../Jetstream/Label'
import JetSecondaryButton from './../../Jetstream/SecondaryButton'
import JetSectionBorder from './../../Jetstream/SectionBorder'

export default {
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
        JetSectionBorder,
    },

    props: [
        'model',
        'fields',
        'objects',
    ],

    data() {
        return {
            deleteObjectForm: this.$inertia.form(),

            displayingObject: false,
            managingPermissionsFor: null,
            objectBeingDeleted: null,
        }
    },

    methods: {
        createObject() {
            this.createObjectForm.post('/user/' + this.model, Object.keys(this.fields).map(field => ({
                field,
                value: ''
            })).reduce((accum, val) => {
                accum[val.field] = val.value
                return accum;
            }, {})).then(response => {
                if (!this.createObjectForm.hasErrors()) {
                    this.displayingObject = true
                }
            })
        },

        confirmObjectDeletion(object) {
            this.objectBeingDeleted = object
        },

        deleteObject() {
            this.deleteObjectForm.delete('/user/' + this.model + '/' + this.objectBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.objectBeingDeleted = null
            })
        },

        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },
    },
}
</script>
