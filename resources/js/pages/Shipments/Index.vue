<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import shipments from '@/routes/shipments';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shipments',
        href: shipments.index().url,
    }
];

const props = defineProps({
    shipments: Object,
});

// Navegar para a tela de criação
function goToCreate() {
    console.log('clicked');
    router.visit(shipments.create().url);
}
</script>

<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <!-- Header com botão -->
                <div class="flex justify-between items-center px-4 py-4">
                    <h1 class="text-xl font-semibold">Shipments</h1>

                    <button
                        type="button"
                        @click="goToCreate"
                        class="bg-primary text-black px-4 py-2 rounded-lg shadow hover:bg-primary/90"
                    >
                        + Adicionar
                    </button>
                </div>

                <!-- Tabela -->
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 dark:bg-gray-800">
                                <th class="p-3">ID</th>
                                <th class="p-3">Tracking</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Criado</th>
                                <th class="p-3">#</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="s in props.shipments.data"
                                :key="s.id"
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                            >
                                <td class="p-3">{{ s.id }}</td>
                                <td class="p-3">{{ s.tracking }}</td>
                                <td class="p-3">{{ s.status }}</td>
                                <td class="p-3">{{ s.created_at }}</td>
                                <td class="p-3">
                                    <a
                                        :href="shipments.show(s.id).url"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                                    >
                                        Details
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
