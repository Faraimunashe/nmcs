<template>
  <Head title="Admin - Institutions" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Institutions</h1>
        <p class="mt-1 text-sm text-slate-500">Universities and colleges that attendees can select.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-5" @submit.prevent="submitInstitution" enctype="multipart/form-data">
          <SelectInput
            v-model="institutionForm.region_id"
            label="Region"
            :options="regionOptions"
            placeholder="Select region"
            :error="institutionForm.errors.region_id"
            required
          />
          <TextInput
            v-model="institutionForm.code"
            label="Code"
            placeholder="Short code"
            :error="institutionForm.errors.code"
          />
          <TextInput
            v-model="institutionForm.name"
            label="Institution Name"
            placeholder="University or college name"
            :error="institutionForm.errors.name"
            required
          />
          <div class="flex flex-col">
            <label class="mb-1 block text-xs font-semibold text-slate-600">Logo (optional)</label>
            <input
              type="file"
              accept="image/*"
              @change="onLogoChange"
              class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
            />
            <p v-if="institutionForm.errors.logo" class="mt-1 text-xs text-red-600">
              {{ institutionForm.errors.logo }}
            </p>
          </div>
          <div class="flex items-end">
            <PrimaryButton type="submit" :processing="institutionForm.processing" size="sm">
              Add Institution
            </PrimaryButton>
          </div>
        </form>

        <div v-if="institutions && institutions.length > 0" class="space-y-2 max-h-72 overflow-y-auto">
          <div
            v-for="institution in institutions"
            :key="institution.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div class="flex items-center gap-3">
              <div v-if="institution.logo" class="h-8 w-8 rounded-full bg-white ring-1 ring-slate-200 flex items-center justify-center overflow-hidden">
                <img
                  :src="`/storage/${institution.logo}`"
                  alt="Logo"
                  class="max-h-7 max-w-7 object-contain"
                />
              </div>
              <p class="text-sm font-semibold text-slate-900">
                {{ institution.name }}
                <span v-if="institution.code" class="text-xs text-slate-500">({{ institution.code }})</span>
              </p>
              <p class="text-xs text-slate-500">
                Region:
                {{
                  regions.find((r) => r.id === institution.region_id)?.name || 'Unknown'
                }}
              </p>
            </div>
            <div class="flex items-center gap-3">
              <button
                type="button"
                class="text-xs font-semibold text-emerald-600 hover:text-emerald-900"
                @click="openEditInstitution(institution)"
              >
                <i class="fa-solid fa-pen-to-square mr-1"></i>
                Edit
              </button>
              <button
                type="button"
                class="text-xs font-semibold text-red-600 hover:text-red-900"
                @click="deleteInstitution(institution)"
              >
                <i class="fa-solid fa-trash-can mr-1"></i>
                Delete
              </button>
            </div>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-building-columns"
          title="No institutions"
          description="Add institutions that students can select when registering."
        />
      </div>
    </Card>

    <div
      v-if="showEditDialog"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeEditInstitution"
    >
      <Card class="w-full max-w-lg mx-4">
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-slate-900">Edit Institution</h3>
            <p class="text-xs text-slate-500">Update region, name, code, or logo for this institution.</p>
          </div>
          <form class="space-y-4" @submit.prevent="updateInstitution" enctype="multipart/form-data">
            <SelectInput
              v-model="editForm.region_id"
              label="Region"
              :options="regionOptions"
              placeholder="Select region"
              :error="editForm.errors.region_id"
              required
            />
            <TextInput
              v-model="editForm.code"
              label="Code"
              placeholder="Short code"
              :error="editForm.errors.code"
            />
            <TextInput
              v-model="editForm.name"
              label="Institution Name"
              placeholder="University or college name"
              :error="editForm.errors.name"
              required
            />
            <div class="flex flex-col gap-2">
              <label class="mb-1 block text-xs font-semibold text-slate-600">Logo (optional)</label>
              <input
                type="file"
                accept="image/*"
                @change="onEditLogoChange"
                class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
              />
              <p v-if="editForm.errors.logo" class="mt-1 text-xs text-red-600">
                {{ editForm.errors.logo }}
              </p>
            </div>
            <div class="flex items-center justify-end gap-3">
              <button
                type="button"
                class="text-xs font-semibold text-slate-500 hover:text-slate-800"
                @click="closeEditInstitution"
              >
                Cancel
              </button>
              <PrimaryButton type="submit" :processing="editForm.processing" size="sm">
                Save Changes
              </PrimaryButton>
            </div>
          </form>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import Layout from '../../../../Shared/Layout.vue';
import Card from '../../../../Shared/Components/Card.vue';
import TextInput from '../../../../Shared/Components/TextInput.vue';
import SelectInput from '../../../../Shared/Components/SelectInput.vue';
import PrimaryButton from '../../../../Shared/Components/PrimaryButton.vue';
import EmptyState from '../../../../Shared/Components/EmptyState.vue';
import { computed, ref } from 'vue';

const props = defineProps({
  institutions: Array,
  regions: Array,
});

const institutionForm = useForm({
  region_id: '',
  code: '',
  name: '',
  logo: null,
});

const showEditDialog = ref(false);
const editingInstitution = ref(null);
const editForm = useForm({
  region_id: '',
  code: '',
  name: '',
  logo: null,
});

const regionOptions = computed(() => {
  if (!props.regions || props.regions.length === 0) {
    return [];
  }

  return props.regions.map((region) => ({
    value: String(region.id),
    label: region.name,
  }));
});

const submitInstitution = () => {
  const payload = {
    ...institutionForm.data(),
    region_id: institutionForm.region_id,
  };

  institutionForm.post('/admin/settings/institutions', {
    preserveScroll: true,
    data: payload,
    onSuccess: () => institutionForm.reset('region_id', 'code', 'name', 'logo'),
  });
};

const onLogoChange = (event) => {
  const file = event.target.files[0] || null;
  institutionForm.logo = file;
};

const onEditLogoChange = (event) => {
  const file = event.target.files[0] || null;
  editForm.logo = file;
};

const openEditInstitution = (institution) => {
  editingInstitution.value = institution;
  showEditDialog.value = true;
  editForm.region_id = String(institution.region_id);
  editForm.code = institution.code || '';
  editForm.name = institution.name || '';
  editForm.logo = null;
};

const closeEditInstitution = () => {
  showEditDialog.value = false;
  editingInstitution.value = null;
  editForm.reset('region_id', 'code', 'name', 'logo');
};

const updateInstitution = () => {
  if (!editingInstitution.value) return;

  const payload = {
    ...editForm.data(),
    region_id: editForm.region_id,
  };

  editForm.post(`/admin/settings/institutions/${editingInstitution.value.id}`, {
    preserveScroll: true,
    data: { ...payload, _method: 'PUT' },
    onSuccess: () => {
      closeEditInstitution();
    },
  });
};

const deleteInstitution = (institution) => {
  if (!confirm(`Delete institution "${institution.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/institutions/${institution.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>

