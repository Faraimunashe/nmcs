<template>
  <Head title="Complete Your Profile" />
  
  <div class="space-y-6">
    <div class="text-center">
      <h1 class="text-2xl font-semibold text-slate-900">Complete Your Profile</h1>
      <p class="mt-1 text-sm text-slate-500">Please provide all required information to continue</p>
    </div>

    <div class="flex items-center justify-center gap-2 mb-6">
      <div
        v-for="(step, index) in steps"
        :key="index"
        class="flex items-center"
      >
        <div
          :class="[
            'flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold',
            currentStep > index
              ? 'bg-emerald-600 text-white'
              : currentStep === index
              ? 'bg-emerald-100 text-emerald-700 ring-2 ring-emerald-600'
              : 'bg-slate-100 text-slate-500'
          ]"
        >
          <i v-if="currentStep > index" class="fas fa-check"></i>
          <span v-else>{{ index + 1 }}</span>
        </div>
        <div v-if="index < steps.length - 1" class="h-1 w-12" :class="currentStep > index ? 'bg-emerald-600' : 'bg-slate-200'"></div>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <Card>
        <div v-if="currentStep === 0" class="space-y-5">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">Personal Information</h2>
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <TextInput
              v-model="form.firstnames"
              label="First Names"
              placeholder="e.g. Farai Tinashe"
              required
              :error="form.errors.firstnames"
            />
            <TextInput
              v-model="form.surname"
              label="Surname"
              placeholder="e.g. Moyo"
              required
              :error="form.errors.surname"
            />
          </div>

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <SelectInput
              v-model="form.gender"
              label="Gender"
              :options="[
                { value: 'MALE', label: 'Male' },
                { value: 'FEMALE', label: 'Female' }
              ]"
              required
              :error="form.errors.gender"
            />
            <TextInput
              v-model="form.nationalid"
              label="National ID"
              placeholder="e.g. 63-123456-A12"
              :error="form.errors.nationalid"
              hint="Optional but recommended"
            />
          </div>

          <div>
            <SelectInput
              v-model="selectedRegion"
              label="Region"
              placeholder="Select region"
              :options="regionOptions"
            />
          </div>
          <div v-if="selectedRegion">
            <SelectInput
              v-model="form.institution_id"
              label="Institution"
              :options="institutionOptions"
              :error="form.errors.institution_id"
              placeholder="Select an institution"
            />
          </div>

          <TextInput
            v-model="form.address"
            label="Address"
            placeholder="Full residential address"
            :error="form.errors.address"
          />
        </div>

        <div v-if="currentStep === 1" class="space-y-4">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">Contact Information</h2>
          <div v-for="(phone, index) in form.phones" :key="index" class="flex gap-3">
            <TextInput
              v-model="form.phones[index]"
              :label="index === 0 ? 'Phone Number' : 'Additional Phone'"
              placeholder="+263 77 123 4567"
              required
              :error="form.errors[`phones.${index}`]"
              class="flex-1"
            />
            <button
              v-if="form.phones.length > 1"
              type="button"
              @click="removePhone(index)"
              class="mt-8 h-10 w-10 rounded-2xl bg-red-50 text-red-600 ring-1 ring-red-200 hover:bg-red-100"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
          <button
            type="button"
            @click="addPhone"
            class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
          >
            <i class="fas fa-plus mr-2"></i>Add another phone number
          </button>
        </div>

        <div v-if="currentStep === 2" class="space-y-3">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">Membership</h2>
          <p class="text-sm text-slate-600">Select membership type</p>
          <SelectInput
            v-model="form.membership_id"
            label="Membership"
            :options="membershipOptions"
            :error="form.errors.membership_id"
            placeholder="Select a membership"
          />
        </div>

        <div v-if="currentStep === 3" class="space-y-4">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">Next of Kin</h2>
          <div v-for="(nok, index) in form.next_of_kins" :key="index" class="space-y-4 p-4 rounded-2xl bg-slate-50 ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
              <h3 class="text-sm font-semibold text-slate-700">Next of Kin {{ index + 1 }}</h3>
              <button
                v-if="form.next_of_kins.length > 1"
                type="button"
                @click="removeNextOfKin(index)"
                class="text-sm text-red-600 hover:text-red-800"
              >
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <TextInput
                v-model="form.next_of_kins[index].relationship"
                label="Relationship"
                placeholder="e.g. Father, Mother, Spouse"
                :error="form.errors[`next_of_kins.${index}.relationship`]"
              />
              <TextInput
                v-model="form.next_of_kins[index].fullname"
                label="Full Name"
                placeholder="Full name"
                :error="form.errors[`next_of_kins.${index}.fullname`]"
              />
              <TextInput
                v-model="form.next_of_kins[index].phone"
                label="Phone Number"
                placeholder="+263 77 123 4567"
                :error="form.errors[`next_of_kins.${index}.phone`]"
              />
            </div>
          </div>
          <button
            type="button"
            @click="addNextOfKin"
            class="text-sm font-semibold text-emerald-700 hover:text-emerald-800"
          >
            <i class="fas fa-plus mr-2"></i>Add another next of kin
          </button>
        </div>

        <div v-if="currentStep === 4" class="space-y-6">
          <h2 class="text-lg font-semibold text-slate-900 mb-4">Health & Special Needs</h2>
          <div>
            <p class="text-sm font-medium text-slate-700 mb-3">Disabilities</p>
            <div class="space-y-2">
              <div v-for="disability in disabilities" :key="disability.id" class="flex items-start">
                <label class="flex items-start gap-3">
                  <input
                    type="checkbox"
                    :value="disability.id"
                    :checked="form.disability_ids.includes(disability.id)"
                    @change="toggleDisability(disability.id)"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
                  />
                  <span class="text-sm text-slate-600">{{ disability.name }}</span>
                </label>
              </div>
            </div>
          </div>

          <div>
            <p class="text-sm font-medium text-slate-700 mb-3">Dietary Requirements</p>
            <div class="space-y-2">
              <div v-for="dietary in dietaries" :key="dietary.id" class="flex items-start">
                <label class="flex items-start gap-3">
                  <input
                    type="checkbox"
                    :value="dietary.id"
                    :checked="form.dietary_ids.includes(dietary.id)"
                    @change="toggleDietary(dietary.id)"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
                  />
                  <span class="text-sm text-slate-600">{{ dietary.name }}</span>
                </label>
              </div>
            </div>
          </div>

          <div>
            <p class="text-sm font-medium text-slate-700 mb-3">Chronic Conditions</p>
            <div class="space-y-2">
              <div v-for="condition in chronicConditions" :key="condition.id" class="flex items-start">
                <label class="flex items-start gap-3">
                  <input
                    type="checkbox"
                    :value="condition.id"
                    :checked="form.chronic_condition_ids.includes(condition.id)"
                    @change="toggleChronicCondition(condition.id)"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
                  />
                  <span class="text-sm text-slate-600">{{ condition.name }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <div class="flex items-center justify-between">
        <button
          v-if="currentStep > 0"
          type="button"
          @click="previousStep"
          class="text-sm font-semibold text-slate-600 hover:text-slate-800"
        >
          <i class="fas fa-arrow-left mr-2"></i>Previous
        </button>
        <div v-else></div>

        <PrimaryButton
          v-if="currentStep < steps.length - 1"
          type="button"
          @click="nextStep"
          icon="fas fa-arrow-right"
        >
          Next Step
        </PrimaryButton>
        <button
          v-else
          type="submit"
          :disabled="form.processing"
          class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-[15px] font-semibold shadow-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-emerald-600 text-white shadow-emerald-600/20 hover:bg-emerald-500 focus:ring-emerald-400/40"
        >
          <i v-if="!form.processing" class="fas fa-save"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          <span v-if="form.processing">Submitting...</span>
          <span v-else>Complete Profile</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import TextInput from '../../Shared/Components/TextInput.vue';
import SelectInput from '../../Shared/Components/SelectInput.vue';
import PrimaryButton from '../../Shared/Components/PrimaryButton.vue';
import { ref, computed, watch } from 'vue';

const currentStep = ref(0);
const steps = [
  'Personal Info',
  'Contact',
  'Membership',
  'Next of Kin',
  'Health & Needs'
];

const props = defineProps({
  memberships: Array,
  regions: Array,
  disabilities: Array,
  dietaries: Array,
  chronicConditions: Array,
  paymentMethods: Array,
  paymentRecipients: Array,
});

const selectedRegion = ref('');

const form = useForm({
  firstnames: '',
  surname: '',
  gender: '',
  address: '',
  nationalid: '',
  institution_id: '',
  phones: [''],
  membership_id: '',
  next_of_kins: [{ relationship: '', fullname: '', phone: '' }],
  disability_ids: [],
  dietary_ids: [],
  chronic_condition_ids: [],
});

const regionOptions = computed(() => {
  return props.regions?.map(region => ({
    value: region.id,
    label: region.name
  })) || [];
});

const institutionOptions = computed(() => {
  if (!selectedRegion.value) return [];
  const region = props.regions?.find(r => String(r.id) === String(selectedRegion.value));
  return region?.institutions?.map(institution => ({
    value: institution.id,
    label: `${institution.name} (${institution.code})`
  })) || [];
});

const membershipOptions = computed(() => {
  return props.memberships?.map(membership => ({
    value: membership.id,
    label: `${membership.status}${membership.description ? ' - ' + membership.description : ''}`
  })) || [];
});

watch(selectedRegion, () => {
  form.institution_id = '';
});

const addPhone = () => {
  form.phones.push('');
};

const removePhone = (index) => {
  form.phones.splice(index, 1);
};

const addNextOfKin = () => {
  form.next_of_kins.push({ relationship: '', fullname: '', phone: '' });
};

const removeNextOfKin = (index) => {
  form.next_of_kins.splice(index, 1);
};


const toggleDisability = (id) => {
  const index = form.disability_ids.indexOf(id);
  if (index > -1) {
    form.disability_ids.splice(index, 1);
  } else {
    form.disability_ids.push(id);
  }
};

const toggleDietary = (id) => {
  const index = form.dietary_ids.indexOf(id);
  if (index > -1) {
    form.dietary_ids.splice(index, 1);
  } else {
    form.dietary_ids.push(id);
  }
};

const toggleChronicCondition = (id) => {
  const index = form.chronic_condition_ids.indexOf(id);
  if (index > -1) {
    form.chronic_condition_ids.splice(index, 1);
  } else {
    form.chronic_condition_ids.push(id);
  }
};

const nextStep = () => {
  if (validateCurrentStep()) {
    currentStep.value++;
  }
};

const previousStep = () => {
  currentStep.value--;
};

const validateCurrentStep = () => {
  if (currentStep.value === 0) {
    if (!form.firstnames || !form.surname || !form.gender) {
      return false;
    }
  } else if (currentStep.value === 1) {
    if (!form.phones[0]) {
      return false;
    }
  }
  return true;
};

const handleSubmit = () => {
  const cleanedPhones = form.phones.filter(phone => phone && phone.trim() !== '');
  if (cleanedPhones.length === 0) {
    alert('Please provide at least one phone number');
    return;
  }
  
  form.phones = cleanedPhones;
  form.next_of_kins = form.next_of_kins.filter(nok => nok.relationship || nok.fullname || nok.phone);
  
  if (!form.address || form.address.trim() === '') form.address = null;
  if (!form.nationalid || form.nationalid.trim() === '') form.nationalid = null;

  form.post('/attendants', {
    onSuccess: () => {
      window.location.href = '/dashboard';
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    }
  });
};
</script>

<script>
export default {
  layout: Layout
};
</script>
