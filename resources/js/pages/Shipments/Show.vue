<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import shipments from '@/routes/shipments';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shipments',
        href: shipments.index().url,
    },
    {
        title: 'Show',
        href: '',
    }
];

// recebendo os dados do backend via props
const props = defineProps({
    shipment: Object,
});

// function
// TODO: implementar a funcionalidade de impressão
const printShipment = () => {
    window.print();
};

</script>

<template>
    <Head title="Shipment Details" />
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Shipment Details</h1>
            <button
              @click="printShipment"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              Print
            </button>
          </div>

          <!-- To Address -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">To Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><strong>Name:</strong> {{ props.shipment.to_address.name }}</div>
              <div><strong>Street:</strong> {{ props.shipment.to_address.street1 }}</div>
              <div><strong>City:</strong> {{ props.shipment.to_address.city }}</div>
              <div><strong>State:</strong> {{ props.shipment.to_address.state }}</div>
              <div><strong>ZIP:</strong> {{ props.shipment.to_address.zip }}</div>
              <div><strong>Country:</strong> {{ props.shipment.to_address.country }}</div>
              <div><strong>Phone:</strong> {{ props.shipment.to_address.phone }}</div>
              <div><strong>Email:</strong> {{ props.shipment.to_address.email }}</div>
            </div>
          </div>
      
          <!-- From Address -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">From Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><strong>Name:</strong> {{ props.shipment.from_address.name }}</div>
              <div><strong>Street:</strong> {{ props.shipment.from_address.street1 }}</div>
              <div><strong>City:</strong> {{ props.shipment.from_address.city }}</div>
              <div><strong>State:</strong> {{ props.shipment.from_address.state }}</div>
              <div><strong>ZIP:</strong> {{ props.shipment.from_address.zip }}</div>
              <div><strong>Country:</strong> {{ props.shipment.from_address.country }}</div>
              <div><strong>Phone:</strong> {{ props.shipment.from_address.phone }}</div>
              <div><strong>Email:</strong> {{ props.shipment.from_address.email }}</div>
            </div>
          </div>
      
          <!-- Parcel -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">Parcel</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div><strong>Length:</strong> {{ props.shipment.parcel.length }}</div>
              <div><strong>Width:</strong> {{ props.shipment.parcel.width }}</div>
              <div><strong>Height:</strong> {{ props.shipment.parcel.height }}</div>
              <div><strong>Weight:</strong> {{ props.shipment.parcel.weight }}</div>
            </div>
          </div>
      
        </div>
    </AppLayout>
</template>
