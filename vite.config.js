import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/components/modals/bind-patient-modal.js',
                'resources/js/components/modals/delete-appointment-modal.js',
                'resources/js/components/modals/delete-document-modal.js',
                'resources/js/components/modals/delete-patient-modal.js',
                'resources/js/components/modals/edit-appointment-modal.js',
                'resources/js/components/modals/edit-document-modal-sec.js',
                'resources/js/components/modals/edit-document-modal.js',
                'resources/js/components/modals/edit-patient-modal.js',
                'resources/js/components/modals/generate-document-modal.js',
                'resources/js/components/modals/modal.js',
                'resources/js/components/modals/view-appointment-modal.js',
                'resources/js/components/modals/view-patient-modal.js',
                'resources/js/components/patients/patients-table.js',
                'resources/js/pages/accounts-table.js',
                'resources/js/pages/appointment-all-create.js',
                'resources/js/pages/appointment-create.js',
                'resources/js/pages/appointment-queue.js',
                'resources/js/pages/appointment-slots.js',
                'resources/js/pages/appointment-table.js',
                'resources/js/pages/appointment-slots.js',
                'resources/js/pages/audit-index.js',
                'resources/js/pages/backup-patients-index.js',
                'resources/js/pages/patient-documents-index.js',
                'resources/js/pages/patient-documents-table.js',
                'resources/js/pages/patient-show-index.js',
                'resources/js/pages/patients-index.js',
                'resources/js/patient-screen/appointments/appointment-cancel.js',
                'resources/js/patient-screen/appointments/appointment-table.js',
                'resources/js/refactored/patient-documents-sec.js',
                'resources/js/refactored/patient-documents.js',
            ],
            refresh: true, // Live reload for development
        }),
    ]
    // ,
    // server: {
    //     host: '0.0.0.0', // Dev server binds to all IPs
    //     port: 4000,
    // },
    // build: {
    //     outDir: 'public/build', // Production build folder
    // },//comment this when in dev mode , uncomment if in build
});