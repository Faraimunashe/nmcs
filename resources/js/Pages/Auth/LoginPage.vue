<template>
  <Head title="Login" />
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
          Mobile-first • Green & White
        </span>
      </div>
    </header>

    <!-- Main Content -->
    <main class="mx-auto max-w-6xl px-4">
      <div class="grid min-h-[calc(100vh-96px)] items-center py-8 sm:py-12 lg:grid-cols-2 lg:gap-12">

        <!-- Left Side (Desktop Only) -->
        <section class="hidden lg:block">
          <div class="max-w-lg">
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">
              Welcome 👋
            </h1>

            <p class="mt-4 text-base text-slate-600">
              Sign in to manage attendants, verify payments and capture information efficiently.
              Designed primarily for mobile use.
            </p>

            <div class="mt-8 space-y-4">
              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Optimised for Phones</p>
                  <p class="text-xs text-slate-500">Large touch-friendly inputs.</p>
                </div>
              </div>

              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Clear Workflows</p>
                  <p class="text-xs text-slate-500">Minimal distractions, fast entry.</p>
                </div>
              </div>

              <div class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-emerald-100 shadow-sm">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                  <span class="text-emerald-700 font-bold">✓</span>
                </div>
                <div>
                  <p class="text-sm font-semibold">Secure & Professional</p>
                  <p class="text-xs text-slate-500">Clean and trusted interface.</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Login Card -->
        <section class="w-full">
          <div class="mx-auto w-full max-w-md">

            <div class="rounded-3xl bg-white ring-1 ring-slate-200 shadow-xl shadow-emerald-900/5">

              <div class="p-6 sm:p-8">

                <div class="text-center">
                  <h2 class="text-2xl font-semibold text-slate-900">
                    Sign in
                  </h2>
                  <p class="mt-2 text-sm text-slate-500">
                    Enter your credentials to continue
                  </p>
                </div>

                <div v-if="$page.props.flash?.success" class="mt-5 rounded-2xl bg-emerald-50 ring-1 ring-emerald-200 px-4 py-3 text-sm text-emerald-700">
                  {{ $page.props.flash.success }}
                </div>

                <div v-if="errors.email" class="mt-5 rounded-2xl bg-red-50 ring-1 ring-red-200 px-4 py-3 text-sm text-red-700">
                  {{ errors.email }}
                </div>

                <form @submit.prevent="submit" class="mt-6 space-y-5">
                  <div>
                    <label class="text-sm font-medium text-slate-700">
                      Email
                    </label>
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

                  <div>
                    <label class="text-sm font-medium text-slate-700">
                      Password
                    </label>
                    <div class="relative mt-2">
                      <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="••••••••"
                        required
                        :class="[
                          'w-full rounded-2xl bg-white px-4 py-3 pr-12 text-[15px] text-slate-900 placeholder:text-slate-400 ring-1',
                          errors.password ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40' : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500'
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
                  </div>

                  <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                      <input
                        v-model="form.remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
                      />
                      Remember me
                    </label>

                    <a href="#" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                      Forgot password?
                    </a>
                  </div>

                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-[15px] font-semibold text-white shadow-lg shadow-emerald-600/20
                           hover:bg-emerald-500 active:bg-emerald-600/90 focus:outline-none focus:ring-2 focus:ring-emerald-400/40
                           disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign in</span>
                  </button>

                  <p class="text-center text-xs text-slate-500 pt-2">
                    For authorised conference administrators only.
                  </p>
                  <p class="text-center text-xs text-slate-500 pt-2">
                    Don't have an account?
                    <Link href="/register" class="font-semibold text-emerald-700 hover:text-emerald-800">
                      Register
                    </Link>
                  </p>
                </form>

              </div>

              <!-- Footer -->
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

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};

defineProps({
  errors: Object,
});
</script>
