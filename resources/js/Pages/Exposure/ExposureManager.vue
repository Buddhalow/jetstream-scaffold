<template>
    <div>
        <!-- Generate API Exposure -->
        <jet-form-section @submitted="createExposure">
            <template #title>
                Create Exposure
            </template>

            <template #description>
                An exposure is when you get featured in a newspaper
            </template>

            <template #form>
                <!-- Exposure Name -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="name" value="Name" />
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createExposureForm.name" autofocus />
                    <jet-label for="description" value="Description" />
                    <jet-input id="description" type="text" class="mt-1 block w-full" v-model="createExposureForm.description" />
                    <jet-label for="outlet" value="Outlet" />
                    <jet-input id="outlet" type="text" class="mt-1 block w-full" v-model="createExposureForm.outlet" />
                    <jet-label for="exposured" value="Exposured" />
                    <jet-input id="exposured" type="datetime-local" class="mt-1 block w-full" v-model="createExposureForm.exposured" />
                    <jet-label for="exposured" value="Medium" />
                    <select name="medium" class="form-input rounded-md shadow-sm" v-model="createExposureForm.medium">
                        <option value="print">Print</option>
                        <option value="broadcast">Broadcast</option>
                    </select>
                    <jet-label for="type" value="Exposured" />

                    <select name="type" class="form-input rounded-md shadow-sm" v-model="createExposureForm.type">
                        <option value="written">Written</option>
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                    </select>
                    <jet-label for="reach" value="Reach" />
                    <jet-input id="reach" type="number" v-mode="createExposureForm.reach" />
                    <jet-input-error :message="createExposureForm.error('name')" class="mt-2" />
                    <jet-input-error :message="createExposureForm.error('description')" class="mt-2" />
                    <jet-input-error :message="createExposureForm.error('outlet')" class="mt-2" />
                    <jet-input-error :message="createExposureForm.error('exposured')" class="mt-2" />

                    <jet-input-error :message="createExposureForm.error('medium')" class="mt-2" />
                    <jet-input-error :message="createExposureForm.error('type')" class="mt-2" />
                    <jet-input-error :message="createExposureForm.error('reach')" class="mt-2" />
                </div>

            </template>

            <template #actions>
                <jet-action-message :on="createExposureForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createExposureForm.processing }" :disabled="createExposureForm.processing">
                    Create
                </jet-button>
            </template>
        </jet-form-section>

        <div v-if="exposures.length > 0">
            <jet-section-border />

            <!-- Manage API Exposures -->
            <div class="mt-10 sm:mt-0">
                <jet-action-section>
                    <template #title>
                        Manage API Exposures
                    </template>

                    <template #description>
                        You may delete any of your existing exposures if they are no longer needed.
                    </template>

                    <!-- API Exposure List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="exposure in exposures">
                                <div>
                                    {{ exposure.name }}
                                </div>

                                <div class="flex items-center">
                                    <div class="text-sm text-gray-400" v-if="exposure.last_used_at">
                                        Last used {{ fromNow(exposure.last_used_at) }}
                                    </div>


                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" @click="confirmExposureDeletion(exposure)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>

        <!-- Exposure Value Modal -->
        <jet-dialog-modal :show="displayingExposure" @close="displayingExposure = false">
            <template #title>
                API Exposure
            </template>

            <template #content>
                <div>
                    Please copy your new API exposure. For your security, it won't be shown again.
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500" v-if="$page.jetstream.flash.exposure">
                    {{ $page.jetstream.flash.exposure }}
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="displayingExposure = false">
                    Close
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>

        <!-- API Exposure Permissions Modal -->
        <jet-dialog-modal :show="managingPermissionsFor" @close="managingPermissionsFor = null">
            <template #title>
                API Exposure Permissions
            </template>

            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="permission in availablePermissions">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox" :value="permission" v-model="updateExposureForm.permissions">
                            <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="managingPermissionsFor = null">
                    Nevermind
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="updateExposure" :class="{ 'opacity-25': updateExposureForm.processing }" :disabled="updateExposureForm.processing">
                    Save
                </jet-button>
            </template>
        </jet-dialog-modal>

        <!-- Delete Exposure Confirmation Modal -->
        <jet-confirmation-modal :show="apiExposureBeingDeleted" @close="apiExposureBeingDeleted = null">
            <template #title>
                Delete API Exposure
            </template>

            <template #content>
                Are you sure you would like to delete this API exposure?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="apiExposureBeingDeleted = null">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteExposure" :class="{ 'opacity-25': deleteExposureForm.processing }" :disabled="deleteExposureForm.processing">
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
        'exposures',
        'availablePermissions',
        'defaultPermissions',
    ],

    data() {
        return {
            createExposureForm: this.$inertia.form({
                name: '',
                description: '',
                type: '',
                outlet: '',
                reach: 0,
                medium: '',
                exposured: ''
            }, {
                bag: 'createExposure',
                resetOnSuccess: true,
            }),

            updateExposureForm: this.$inertia.form({
                permissions: []
            }, {
                resetOnSuccess: false,
                bag: 'updateExposure',
            }),

            deleteExposureForm: this.$inertia.form(),

            displayingExposure: false,
            managingPermissionsFor: null,
            apiExposureBeingDeleted: null,
        }
    },

    methods: {
        createExposure() {
            this.createExposureForm.post('/user/exposures', {
                preserveScroll: true,
            }).then(response => {
                if (! this.createExposureForm.hasErrors()) {
                }
            })
        },

        manageExposurePermissions(exposure) {
            this.updateExposureForm.permissions = exposure.abilities

            this.managingPermissionsFor = exposure
        },

        updateExposure() {
            this.updateExposureForm.put('/user/exposures/' + this.managingPermissionsFor.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(response => {
                this.managingPermissionsFor = null
            })
        },

        confirmExposureDeletion(exposure) {
            this.apiExposureBeingDeleted = exposure
        },

        deleteExposure() {
            this.deleteExposureForm.delete('/user/exposures/' + this.apiExposureBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.apiExposureBeingDeleted = null
            })
        },

        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },
    },
}
</script>
