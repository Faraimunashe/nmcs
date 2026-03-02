<template>
  <Head title="Admin - Regions" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Regions</h1>
        <p class="mt-1 text-sm text-slate-500">Geographical regions used to group institutions and attendees.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitRegion">
          <TextInput
            v-model="regionForm.name"
            label="Region Name"
            placeholder="e.g. Harare"
            :error="regionForm.errors.name"
            required
          />
          <div class="md:col-span-2 flex items-end">
            <PrimaryButton type="submit" :processing="regionForm.processing" size="sm">
              Add Region
            </PrimaryButton>
          </div>
        </form>

        <div v-if="regions && regions.length > 0" class="space-y-2">
          <div
            v-for="region in regions"
            :key="region.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ region.name }}</p>
            </div>
            <button
              type="button"
              class="text-xs font-semibold text-red-600 hover:text-red-900"
              @click="deleteRegion(region)"
            >
              <i class="fa-solid fa-trash-can mr-1"></i>
              Delete
            </button>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-map"
          title="No regions"
          description="Add regions to group institutions and students."
        />
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import Layout from '../../../../Shared/Layout.vue';
import Card from '../../../../Shared/Components/Card.vue';
import TextInput from '../../../../Shared/Components/TextInput.vue';
import PrimaryButton from '../../../../Shared/Components/PrimaryButton.vue';
import EmptyState from '../../../../Shared/Components/EmptyState.vue';

const props = defineProps({
  regions: Array,
});

const regionForm = useForm({
  name: '',
});

const submitRegion = () => {
  regionForm.post('/admin/settings/regions', {
    preserveScroll: true,
    onSuccess: () => regionForm.reset('name'),
  });
};

const deleteRegion = (region) => {
  if (!confirm(`Delete region "${region.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/regions/${region.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>

