<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import shipments from '@/routes/shipments';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shipments',
        href: shipments.index().url,
    },
    {
        title: 'Create',
        href: shipments.create().url,
    }
];

const form = useForm({
    to: {
        name: '',
        street1: '',
        city: '',
        state: '',
        zip: '',
        country: '',
        phone: '',
        email: '',
    },
    from: {
        name: '',
        street1: '',
        city: '',
        state: '',
        zip: '',
        country: '',
        phone: '',
        email: '',
    },
    parcel: {
        length: '',
        width: '',
        height: '',
        weight: '',
    }
});


const submit = () => {
    
    form.post(shipments.store().url, {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();
            router.visit(shipments.index().url);
        },

        onError: () => {
            console.log('Validation errors:', form.errors);
        },
    });
};

</script>

<template>
    <Head title="Shipments" />
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
          <h1 class="text-2xl font-bold mb-6">Create Shipment</h1>
          <form @submit.prevent="submit" class="space-y-6">
      
          <!-- To Address -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">To Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              
                <div>
                    <input v-model="form.to.name" placeholder="Name" class="input" />
                    <span v-if="form.errors['to_address.name']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.name'] }}
                    </span>
                </div>
    
                <div>
                    <input v-model="form.to.street1" placeholder="Street" class="input" />
                    <span v-if="form.errors['to_address.street1']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.street1'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.to.city" placeholder="City" class="input" />
                    <span v-if="form.errors['to_address.city']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.city'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.to.state" placeholder="State" class="input" />
                    <span v-if="form.errors['to_address.state']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.state'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.to.zip" placeholder="ZIP" class="input" />
                    <span v-if="form.errors['to_address.zip']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.zip'] }}
                    </span>
                </div>
                
                <div>
                    <input v-model="form.to.country" placeholder="Country" class="input" />
                    <span v-if="form.errors['to_address.country']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.country'] }}
                    </span>
                </div>
              
                <div>
                    <input v-model="form.to.phone" placeholder="Phone" class="input" />
                    <span v-if="form.errors['to_address.phone']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.phone'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.to.email" placeholder="Email" class="input" />
                    <span v-if="form.errors['to_address.email']" class="text-red-500 text-sm">
                    {{ form.errors['to_address.email'] }}
                    </span>
                </div>
            </div>
          </div>
      
          <!-- From Address -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">From Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <input v-model="form.from.name" placeholder="Name" class="input" />
                    <span v-if="form.errors['from_address.name']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.name'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.street1" placeholder="Street" class="input" />
                    <span v-if="form.errors['from_address.street1']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.street1'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.city" placeholder="City" class="input" />
                    <span v-if="form.errors['from_address.city']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.city'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.state" placeholder="State" class="input" />
                    <span v-if="form.errors['from_address.state']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.state'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.zip" placeholder="ZIP" class="input" />
                    <span v-if="form.errors['from_address.zip']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.zip'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.country" placeholder="Country" class="input" />
                    <span v-if="form.errors['from_address.country']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.country'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.phone" placeholder="Phone" class="input" />
                    <span v-if="form.errors['from_address.phone']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.phone'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.from.email" placeholder="Email" class="input" />
                    <span v-if="form.errors['from_address.email']" class="text-red-500 text-sm">
                    {{ form.errors['from_address.email'] }}
                    </span>
                </div>
            </div>
          </div>
      
          <!-- Parcel -->
          <div class="shadow rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">Parcel</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input v-model="form.parcel.length" placeholder="Length" class="input" />
                    <span v-if="form.errors['parcel.length']" class="text-red-500 text-sm">
                    {{ form.errors['parcel.length'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.parcel.width" placeholder="Width" class="input" />
                    <span v-if="form.errors['parcel.width']" class="text-red-500 text-sm">
                    {{ form.errors['parcel.width'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.parcel.height" placeholder="Height" class="input" />
                    <span v-if="form.errors['parcel.height']" class="text-red-500 text-sm">
                    {{ form.errors['parcel.height'] }}
                    </span>
                </div>

                <div>
                    <input v-model="form.parcel.weight" placeholder="Weight" class="input" />
                    <span v-if="form.errors['parcel.weight']" class="text-red-500 text-sm">
                    {{ form.errors['parcel.weight'] }}
                    </span>
                </div>
            </div>
          </div>
      
          <!-- Submit -->
          <button 
            type="submit"
            @click="submit" 
            class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Submit</button>

          </form>
        </div>
    </AppLayout>

</template>