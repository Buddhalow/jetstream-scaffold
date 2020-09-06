<template>
    <div>
        <!-- Generate Coverage -->
        <jet-form-section @submitted="createCoverage">
            <template #title>
                Create Coverage
            </template>

            <template #description>
                Coverage
            </template>

            <template #form>
                <!-- Coverage Name -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="name" value="Name" />
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createCoverageForm.name" autofocus />
                    <jet-label for="description" value="Description" />
                    <jet-input id="description" type="text" class="mt-1 block w-full" v-model="createCoverageForm.description" />
                    <jet-label for="outlet" value="Outlet" />
                    <jet-input id="outlet" type="text" class="mt-1 block w-full" v-model="createCoverageForm.outlet" />
                    <jet-label for="covered" value="Covered" />
                    <jet-input id="covered" type="datetime-local" class="mt-1 block w-full" v-model="createCoverageForm.covered" />
                    <jet-label for="covered" value="Medium" />
                    <select name="medium" class="form-input rounded-md shadow-sm" v-model="createCoverageForm.medium">
                        <option value="print">Print</option>
                        <option value="broadcast">Broadcast</option>
                    </select>
                    <jet-label for="type" value="Coveraged" />

                    <select name="type" class="form-input rounded-md shadow-sm" v-model="createCoverageForm.type">
                        <option value="written">Written</option>
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                    </select>
                    <jet-label for="reach" value="Reach" />
                    <jet-input-error :message="createCoverageForm.error('name')" class="mt-2" />
                    <jet-input-error :message="createCoverageForm.error('description')" class="mt-2" />
                    <jet-input-error :message="createCoverageForm.error('outlet')" class="mt-2" />
                    <jet-input-error :message="createCoverageForm.error('coveraged')" class="mt-2" />

                    <jet-input-error :message="createCoverageForm.error('medium')" class="mt-2" />
                    <jet-input-error :message="createCoverageForm.error('type')" class="mt-2" />
                    <jet-input-error :message="createCoverageForm.error('reach')" class="mt-2" />
                </div>

            </template>

            <template #actions>
                <jet-action-message :on="createCoverageForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createCoverageForm.processing }" :disabled="createCoverageForm.processing">
                    Create
                </jet-button>
            </template>
        </jet-form-section>

        <div v-if="coverages.length > 0">
            <jet-section-border />

            <!-- Manage Coverages -->
            <div class="mt-10 sm:mt-0">
                <jet-action-section>
                    <template #title>
                        Manage Coverages
                    </template>

                    <template #description>
                        You may delete any of your existing coverages if they are no longer needed.
                    </template>

                    <!-- Coverage List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="coverage in coverages">
                                <div>
                                    {{ coverage.name }}
                                </div>

                                <div class="flex items-center">
                                    <div class="text-sm text-gray-400" v-if="coverage.created_at">
                                        Last used {{ fromNow(coverage.created_at) }}
                                    </div>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" @click="confirmCoverageDeletion(coverage)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>

        <!-- Coverage Value Modal -->
        <jet-dialog-modal :show="displayingCoverage" @close="displayingCoverage = false">
            <template #title>
                Coverage
            </template>

            <template #content>
                <div>
                    Please copy your new Coverage. For your security, it won't be shown again.
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500" v-if="$page.jetstream.flash.coverage">
                    {{ $page.jetstream.flash.coverage }}
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="displayingCoverage = false">
                    Close
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>


        <!-- Delete Coverage Confirmation Modal -->
        <jet-confirmation-modal :show="coverageBeingDeleted" @close="coverageBeingDeleted = null">
            <template #title>
                Delete Coverage
            </template>

            <template #content>
                Are you sure you would like to delete this Coverage?
            </template>

            <template #footer>
                <jet-secondary-button @click.native="coverageBeingDeleted = null">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteCoverage" :class="{ 'opacity-25': deleteCoverageForm.processing }" :disabled="deleteCoverageForm.processing">
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
        'coverages',
        'availablePermissions',
        'defaultPermissions',
    ],

    data() {
        return {
            createCoverageForm: this.$inertia.form({
                name: 't',
                description: 't',
                type: 'video',
                medium: 'broadcast',
                reach: 0,
                covered: '2019-05-02',
                outlet: 'Sveriges Television',
            }, {
                bag: 'createCoverage',
                resetOnSuccess: true,
            }),

            updateCoverageForm: this.$inertia.form({
                permissions: []
            }, {
                resetOnSuccess: false,
                bag: 'updateCoverage',
            }),

            deleteCoverageForm: this.$inertia.form(),

            displayingCoverage: false,
            managingPermissionsFor: null,
            coverageBeingDeleted: null,
        }
    },

    methods: {
        createCoverage() {
            this.createCoverageForm.post('/user/coverages', {
                name: 't',
                description: 't',
                type: 'video',
                medium: 'broadcast',
                reach: 0,
                covered: '2019-05-02',
                outlet: 'Sveriges Television',
                preserveScroll: true,
            }).then(response => {
                if (! this.createCoverageForm.hasErrors()) {
                    this.displayingCoverage = true
                }
            })
        },

        manageCoveragePermissions(coverage) {
            this.updateCoverageForm.permissions = coverage.abilities

            this.managingPermissionsFor = coverage
        },

        updateCoverage() {
            this.updateCoverageForm.put('/user/coverages/' + this.managingPermissionsFor.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(response => {
                this.managingPermissionsFor = null
            })
        },

        confirmCoverageDeletion(coverage) {
            this.coverageBeingDeleted = coverage
        },

        deleteCoverage() {
            this.deleteCoverageForm.delete('/user/coverages/' + this.coverageBeingDeleted.id, {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.coverageBeingDeleted = null
            })
        },

        fromNow(timestamp) {
            return moment(timestamp).local().fromNow()
        },
    },
}
</script>
