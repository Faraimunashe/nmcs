<template>
  <Head title="Admin - New User" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Create User</h1>
        <p class="mt-1 text-sm text-slate-500">Add a new user and assign a role</p>
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
            placeholder="e.g. Admin User"
            :error="form.errors.name"
            required
          />
          <TextInput
            v-model="form.email"
            type="email"
            label="Email"
            placeholder="user@example.com"
            :error="form.errors.email"
            required
          />
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <TextInput
            v-model="form.password"
            type="password"
            label="Password"
            placeholder="••••••••"
            :error="form.errors.password"
            required
          />
          <TextInput
            v-model="form.password_confirmation"
            type="password"
            label="Confirm password"
            placeholder="••••••••"
            required
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
            Create User
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
  roles: Array,
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
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
  form.post('/admin/users', {
    onSuccess: () => {
      form.reset('password', 'password_confirmation');
    },
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>

