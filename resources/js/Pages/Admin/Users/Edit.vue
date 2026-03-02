<template>
  <Head :title="`Admin - Edit ${user.name}`" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Edit User</h1>
        <p class="mt-1 text-sm text-slate-500">Update user details and role</p>
      </div>
      <Link href="/admin/users" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Users
      </Link>
    </div>

    <Card>
      <form class="space-y-5" @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <TextInput
            v-model="form.name"
            label="Full name"
            :error="form.errors.name"
            required
          />
          <TextInput
            v-model="form.email"
            type="email"
            label="Email"
            :error="form.errors.email"
            required
          />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <TextInput
            v-model="form.password"
            type="password"
            label="New password"
            placeholder="Leave blank to keep current password"
            :error="form.errors.password"
          />
          <TextInput
            v-model="form.password_confirmation"
            type="password"
            label="Confirm new password"
            placeholder="Repeat new password"
          />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <SelectInput
            v-model="form.role"
            label="Role"
            :options="roleOptions"
            placeholder="Select role"
            :error="form.errors.role"
            required
          />
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
          <Link href="/admin/users" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
            Cancel
          </Link>
          <PrimaryButton type="submit" :processing="form.processing" icon="fa-solid fa-save">
            Save Changes
          </PrimaryButton>
        </div>
      </form>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import TextInput from '../../../Shared/Components/TextInput.vue';
import SelectInput from '../../../Shared/Components/SelectInput.vue';
import PrimaryButton from '../../../Shared/Components/PrimaryButton.vue';
import { computed } from 'vue';

const props = defineProps({
  user: Object,
  roles: Array,
});

const form = useForm({
  name: props.user?.name || '',
  email: props.user?.email || '',
  password: '',
  password_confirmation: '',
  role: props.user?.roles && props.user.roles.length > 0 ? props.user.roles[0] : '',
});

const roleOptions = computed(() => {
  if (!props.roles || props.roles.length === 0) {
    return [];
  }

  return props.roles.map((role) => ({
    value: role.name,
    label: role.display_name || role.name,
  }));
});

const submit = () => {
  form.put(`/admin/users/${props.user.id}`);
};
</script>

<script>
export default {
  layout: Layout,
};
</script>

