<template>
    <Head title="Register" />
  <div class="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-emerald-50 text-slate-900">
    <!-- Top Brand Bar -->
    <header class="mx-auto max-w-6xl px-4 pt-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <!-- Logo -->
          <div class="h-12 w-12 rounded-2xl bg-white ring-1 ring-emerald-100 shadow-sm overflow-hidden">
            <img
              src="./../../../images/nmcs.jpeg"
              alt="NMCS Zimbabwe"
              class="h-full w-full object-contain p-2"
            />
          </div>

          <div class="leading-tight">
            <p class="text-sm font-semibold tracking-tight text-emerald-700">
              NMCS Zimbabwe
            </p>
            <p class="text-xs text-slate-500">
              Easter Conference Registration
            </p>
          </div>
        </div>

        <span class="hidden sm:inline-flex text-xs text-slate-500">
          Mobile-first • Green &amp; White
        </span>
      </div>
    </header>

    <!-- Main -->
    <main class="mx-auto max-w-6xl px-4">
      <div class="grid min-h-[calc(100vh-96px)] items-center py-8 sm:py-12 lg:grid-cols-2 lg:gap-12">

        <!-- Left panel (desktop only) -->
        <section class="hidden lg:block">
          <div class="max-w-lg">
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">
              Create your account ✨
            </h1>

            <p class="mt-4 text-base text-slate-600">
              Register to access the NMCS Zimbabwe conference system. Keep details accurate for smooth verification.
            </p>

            <div class="mt-8 space-y-4">
              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Quick setup</p>
                  <p class="text-xs text-slate-500">Mobile-friendly registration form.</p>
                </div>
              </div>

              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Accurate records</p>
                  <p class="text-xs text-slate-500">Helps with payments and attendance tracking.</p>
                </div>
              </div>

              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Secure access</p>
                  <p class="text-xs text-slate-500">Your login is protected and private.</p>
                </div>
              </div>
            </div>

            <p class="mt-10 text-xs text-slate-500">
              Already have an account? You can sign in anytime.
            </p>
          </div>
        </section>

        <!-- Registration Card -->
        <section class="w-full">
          <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl bg-white ring-1 ring-slate-200 shadow-xl shadow-emerald-900/5">
              <div class="p-6 sm:p-8">
                <div class="text-center">
                  <h2 class="text-2xl font-semibold text-slate-900">
                    Register
                  </h2>
                  <p class="mt-2 text-sm text-slate-500">
                    Fill in your details to create an account
                  </p>
                </div>

                <div v-if="errors.email || errors.password || errors.name" class="mt-5 rounded-2xl bg-red-50 ring-1 ring-red-200 px-4 py-3 text-sm text-red-700">
                  Please fix the highlighted fields.
                </div>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
                  <div>
                    <label class="text-sm font-medium text-slate-700">Full name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      placeholder="e.g. Farai Manjeese"
                      required
                      :class="[
                        'mt-2 w-full rounded-2xl bg-white px-4 py-3 text-[15px] text-slate-900 placeholder:text-slate-400 ring-1',
                        errors.name ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40' : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500'
                      ]"
                    />
                    <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
                    <p v-else class="mt-1 text-xs text-slate-500">Use your real name for verification.</p>
                  </div>

                  <div>
                    <label class="text-sm font-medium text-slate-700">Email</label>
                    <input
                      v-model="form.email"
                      type="email"
                      placeholder="you@example.com"
                      required
                      :class="[
                        'mt-2 w-full rounded-2xl bg-white px-4 py-3 text-[15px] text-slate-900 placeholder:text-slate-400 ring-1',
                        errors.email ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40' : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500'
                      ]"
                    />
                    <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
                  </div>

                  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                      <label class="text-sm font-medium text-slate-700">Password</label>
                      <div class="relative mt-2">
                        <input
                          v-model="form.password"
                          :type="showPassword ? 'text' : 'password'"
                          placeholder="••••••••"
                          required
                          :class="[
                            'w-full rounded-2xl bg-white px-4 py-3 pr-12 text-[15px] text-slate-900 placeholder:text-slate-400 ring-1',
                            (errors.password && !errors.password.includes('confirmation')) ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40' : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500'
                          ]"
                        />
                        <button
                          type="button"
                          @click="showPassword = !showPassword"
                          class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-slate-500 hover:text-slate-800"
                        >
                          {{ showPassword ? 'HIDE' : 'SHOW' }}
                        </button>
                      </div>
                      <p v-if="errors.password && !errors.password.includes('confirmation')" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
                    </div>

                    <div>
                      <label class="text-sm font-medium text-slate-700">Confirm password</label>
                      <div class="relative mt-2">
                        <input
                          v-model="form.password_confirmation"
                          :type="showPasswordConfirmation ? 'text' : 'password'"
                          placeholder="••••••••"
                          required
                          :class="[
                            'w-full rounded-2xl bg-white px-4 py-3 pr-12 text-[15px] text-slate-900 placeholder:text-slate-400 ring-1',
                            (errors.password && errors.password.includes('confirmation')) || errors.password_confirmation ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40' : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500'
                          ]"
                        />
                        <button
                          type="button"
                          @click="showPasswordConfirmation = !showPasswordConfirmation"
                          class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-slate-500 hover:text-slate-800"
                        >
                          {{ showPasswordConfirmation ? 'HIDE' : 'SHOW' }}
                        </button>
                      </div>
                      <p v-if="(errors.password && errors.password.includes('confirmation')) || errors.password_confirmation" class="mt-1 text-xs text-red-600">
                        {{ (errors.password && errors.password.includes('confirmation')) ? errors.password : errors.password_confirmation }}
                      </p>
                    </div>
                  </div>

                  <div class="flex items-start gap-3">
                    <input
                      v-model="form.terms"
                      type="checkbox"
                      required
                      class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
                    />
                    <p class="text-sm text-slate-600">
                      I confirm that the information provided is correct and will be used for conference administration.
                    </p>
                  </div>

                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-[15px] font-semibold text-white shadow-lg shadow-emerald-600/20
                           hover:bg-emerald-500 active:bg-emerald-600/90 focus:outline-none focus:ring-2 focus:ring-emerald-400/40
                           disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="form.processing">Creating account...</span>
                    <span v-else>Create account</span>
                  </button>

                  <div class="pt-2 text-center">
                    <p class="text-xs text-slate-500">
                      Already have an account?
                      <Link href="/login" class="font-semibold text-emerald-700 hover:text-emerald-800">
                        Sign in
                      </Link>
                    </p>
                  </div>
                </form>
              </div>

              <div class="border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500">
                © 2026 NMCS Zimbabwe
              </div>
            </div>

            <div class="h-10"></div>
          </div>
        </section>

      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const submit = () => {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

defineProps({
  errors: Object,
});
</script>
