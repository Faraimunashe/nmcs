<template>
  <Head title="Forgot password" />
  <div class="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-emerald-50 text-slate-900">

    <header class="mx-auto max-w-6xl px-4 pt-6">
      <div class="flex items-center gap-3">
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
    </header>

    <main class="mx-auto max-w-6xl px-4">
      <div class="grid min-h-[calc(100vh-96px)] items-center py-8 sm:py-12 lg:grid-cols-2 lg:gap-12">

        <section class="hidden lg:block">
          <div class="max-w-lg">
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">
              Reset your password
            </h1>
            <p class="mt-4 text-base text-slate-600">
              Enter the email you used to register. We will send you a secure link to choose a new password.
            </p>
          </div>
        </section>

        <section class="w-full">
          <div class="mx-auto w-full max-w-md">
            <div class="rounded-3xl bg-white ring-1 ring-slate-200 shadow-xl shadow-emerald-900/5">
              <div class="p-6 sm:p-8">
                <div class="text-center">
                  <h2 class="text-2xl font-semibold text-slate-900">
                    Forgot password
                  </h2>
                  <p class="mt-2 text-sm text-slate-500">
                    We will email you reset instructions
                  </p>
                </div>

                <div v-if="status" class="mt-5 rounded-2xl bg-emerald-50 ring-1 ring-emerald-200 px-4 py-3 text-sm text-emerald-800">
                  {{ status }}
                </div>

                <div v-if="errors.email" class="mt-5 rounded-2xl bg-red-50 ring-1 ring-red-200 px-4 py-3 text-sm text-red-700">
                  {{ errors.email }}
                </div>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
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
                  </div>

                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-[15px] font-semibold text-white shadow-lg shadow-emerald-600/20
                           hover:bg-emerald-500 active:bg-emerald-600/90 focus:outline-none focus:ring-2 focus:ring-emerald-400/40
                           disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="form.processing">Sending...</span>
                    <span v-else>Email reset link</span>
                  </button>

                  <p class="text-center text-sm text-slate-500">
                    <Link href="/login" class="font-semibold text-emerald-700 hover:text-emerald-800">
                      Back to sign in
                    </Link>
                  </p>
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

defineProps({
  errors: Object,
  status: String,
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post('/forgot-password');
};
</script>
