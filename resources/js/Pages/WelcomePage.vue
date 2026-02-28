<template>
    <Head title="Welcome" />
    <!-- Welcome -->
      <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">
            Welcome back, <span class="text-emerald-700">Farai</span>
          </h1>
          <p class="mt-1 text-sm text-slate-600">
            Here’s your personal view: your captures, your pending items, and quick actions.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Online
          </span>

          <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
            <i class="fa-solid fa-location-dot text-emerald-700"></i>
            Region: National
          </span>
        </div>
      </section>

      <!-- KPI cards (user-centric) -->
      <section class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-user-check mr-2 text-emerald-700"></i> My captures
          </p>
          <p class="mt-2 text-2xl font-semibold">28</p>
          <p class="mt-1 text-xs text-slate-500">Total entries by you</p>
        </div>

        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-circle-check mr-2 text-emerald-700"></i> Approved
          </p>
          <p class="mt-2 text-2xl font-semibold text-emerald-700">19</p>
          <p class="mt-1 text-xs text-slate-500">Verified records</p>
        </div>

        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-hourglass-half mr-2 text-emerald-700"></i> Pending
          </p>
          <p class="mt-2 text-2xl font-semibold">6</p>
          <p class="mt-1 text-xs text-slate-500">Awaiting approval</p>
        </div>

        <div class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm">
          <p class="text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-triangle-exclamation mr-2 text-red-600"></i> Needs fixing
          </p>
          <p class="mt-2 text-2xl font-semibold text-red-600">3</p>
          <p class="mt-1 text-xs text-slate-500">Missing/invalid info</p>
        </div>
      </section>

      <!-- Quick actions -->
      <section class="mt-6">
        <h2 class="text-sm font-semibold text-slate-700">Quick actions</h2>
        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
          <button class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm hover:ring-emerald-200 text-left" type="button">
            <p class="text-sm font-semibold">
              <i class="fa-solid fa-user-plus mr-2 text-emerald-700"></i>
              Capture
            </p>
            <p class="mt-1 text-xs text-slate-500">New attendant entry</p>
          </button>

          <button class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm hover:ring-emerald-200 text-left" type="button">
            <p class="text-sm font-semibold">
              <i class="fa-solid fa-receipt mr-2 text-emerald-700"></i>
              Record payment
            </p>
            <p class="mt-1 text-xs text-slate-500">Deposit / balance / full</p>
          </button>

          <button class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm hover:ring-emerald-200 text-left" type="button">
            <p class="text-sm font-semibold">
              <i class="fa-solid fa-clipboard-check mr-2 text-emerald-700"></i>
              My pending
            </p>
            <p class="mt-1 text-xs text-slate-500">What needs review</p>
          </button>

          <button class="rounded-3xl bg-white p-4 ring-1 ring-slate-200 shadow-sm hover:ring-emerald-200 text-left" type="button">
            <p class="text-sm font-semibold">
              <i class="fa-solid fa-file-export mr-2 text-emerald-700"></i>
              My reports
            </p>
            <p class="mt-1 text-xs text-slate-500">Export your entries</p>
          </button>
        </div>
      </section>

      <!-- Two panels: My recent captures + My pending payments -->
      <section class="mt-6 grid gap-4 lg:grid-cols-2">
        <!-- My recent captures -->
        <div class="rounded-3xl bg-white ring-1 ring-slate-200 shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <div>
              <h3 class="text-sm font-semibold">
                <i class="fa-solid fa-users mr-2 text-emerald-700"></i>
                My recent captures
              </h3>
              <p class="text-xs text-slate-500">Latest attendants you added</p>
            </div>
            <button class="text-sm font-semibold text-emerald-700 hover:text-emerald-800" type="button">View all</button>
          </div>

          <div class="divide-y divide-slate-100">
            <div class="px-5 py-4 flex items-center justify-between gap-3">
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate">Tendai Moyo</p>
                <p class="text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-phone mr-2"></i>+263 77 123 4567 • MSU • Ordinary
                </p>
              </div>
              <span class="shrink-0 inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                Approved
              </span>
            </div>

            <div class="px-5 py-4 flex items-center justify-between gap-3">
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate">Rudo Chirwa</p>
                <p class="text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-phone mr-2"></i>+263 78 555 0192 • GZU • Associate
                </p>
              </div>
              <span class="shrink-0 inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                Pending
              </span>
            </div>

            <div class="px-5 py-4 flex items-center justify-between gap-3">
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate">Kuda Ncube</p>
                <p class="text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-triangle-exclamation mr-2 text-red-600"></i>Missing national ID
                </p>
              </div>
              <span class="shrink-0 inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                Fix
              </span>
            </div>
          </div>
        </div>

        <!-- My pending items -->
        <div class="rounded-3xl bg-white ring-1 ring-slate-200 shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <div>
              <h3 class="text-sm font-semibold">
                <i class="fa-solid fa-hourglass-half mr-2 text-emerald-700"></i>
                My pending items
              </h3>
              <p class="text-xs text-slate-500">Payments/records waiting for action</p>
            </div>
            <button class="text-sm font-semibold text-emerald-700 hover:text-emerald-800" type="button">Open</button>
          </div>

          <div class="divide-y divide-slate-100">
            <div class="px-5 py-4 flex items-start justify-between gap-4">
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate">
                  <i class="fa-solid fa-money-bill-wave mr-2 text-emerald-700"></i>
                  Deposit • EcoCash
                </p>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-hashtag mr-2"></i>Ref: ECA-102933
                </p>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-dollar-sign mr-2"></i>Amount: $20.00
                </p>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-user mr-2"></i>Rudo Chirwa
                </p>
              </div>
              <span class="shrink-0 inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                Pending
              </span>
            </div>

            <div class="px-5 py-4 flex items-start justify-between gap-4">
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate">
                  <i class="fa-solid fa-circle-info mr-2 text-red-600"></i>
                  Missing info • National ID
                </p>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-user mr-2"></i>Kuda Ncube
                </p>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  <i class="fa-solid fa-list-check mr-2"></i>Please update details to proceed
                </p>
              </div>
              <button class="h-9 px-3 rounded-2xl bg-red-50 text-red-700 text-xs font-semibold ring-1 ring-red-200 hover:bg-red-100" type="button">
                Fix now
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Mobile CTA -->
      <div class="mt-6 sm:hidden">
        <button
          type="button"
          class="w-full h-12 rounded-2xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-600/20 hover:bg-emerald-500"
        >
          <i class="fa-solid fa-user-plus mr-2"></i>
          Capture Attendant
        </button>
      </div>
</template>

<script>
import Layout from '../Shared/Layout.vue';

export default {
    layout: Layout,
}
</script>
