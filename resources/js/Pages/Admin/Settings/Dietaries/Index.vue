<template>
  <Head title="Admin - Dietary Options" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Dietary Options</h1>
        <p class="mt-1 text-sm text-slate-500">Dietary requirements used when attendees specify their needs.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="space-y-3" @submit.prevent="submitDietary">
          <TextInput
            v-model="dietaryForm.name"
            label="Name"
            :error="dietaryForm.errors.name"
            required
          />
          <TextInput
            v-model="dietaryForm.description"
            label="Description"
            :error="dietaryForm.errors.description"
          />
        </form>
        <PrimaryButton type="submit" :processing="dietaryForm.processing" size="sm" @click="submitDietary">
          Add Dietary Option
        </PrimaryButton>
        <div v-if="dietaries && dietaries.length > 0" class="space-y-2 max-h-64 overflow-y-auto">
          <div
            v-for="item in dietaries"
            :key="item.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-3 py-2 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-xs font-semibold text-slate-900">{{ item.name }}</p>
              <p class="text-[11px] text-slate-500">{{ item.description || '' }}</p>
            </div>
            <button
              type="button"
              class="text-[11px] font-semibold text-red-600 hover:text-red-900"
              @click="deleteDietary(item)"
            >
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </div>
        </div>
        <p v-else class="text-xs text-slate-500">No dietary options defined.</p>
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

const props = defineProps({
  dietaries: Array,
});

const dietaryForm = useForm({
  name: '',
  description: '',
});

const submitDietary = () => {
  dietaryForm.post('/admin/settings/dietaries', {
    preserveScroll: true,
    onSuccess: () => dietaryForm.reset('name', 'description'),
  });
};

const deleteDietary = (item) => {
  if (!confirm(`Delete dietary option "${item.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/dietaries/${item.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>

