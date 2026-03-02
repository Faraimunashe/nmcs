<template>
  <Head title="Admin - Settings" />

  <div class="space-y-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Configuration</h1>
        <p class="mt-1 text-sm text-slate-500">Choose a settings area to manage.</p>
      </div>
      <Link href="/admin/dashboard" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Dashboard
      </Link>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/conference-fees" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Conference Fees</h2>
              <p class="text-xs text-slate-500">Set and manage conference fees.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
              <i class="fa-solid fa-receipt"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Only one fee is active at a time for balance calculations.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/payment-charges" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Payment Charges</h2>
              <p class="text-xs text-slate-500">Configure service charges.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-sky-50 text-sky-700">
              <i class="fa-solid fa-percent"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used by payment methods when calculating final amounts.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/payment-methods" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Payment Methods</h2>
              <p class="text-xs text-slate-500">Manage payment channels.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
              <i class="fa-solid fa-money-bill-wave"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">For example EcoCash, bank transfer, cash, etc.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/payment-recipients" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Payment Recipients</h2>
              <p class="text-xs text-slate-500">Who receives payments.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-purple-50 text-purple-700">
              <i class="fa-solid fa-user-tag"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Define named recipients for tracking where money goes.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/memberships" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Memberships</h2>
              <p class="text-xs text-slate-500">Membership statuses for students.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-700">
              <i class="fa-solid fa-id-card"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used when capturing student membership information.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/regions" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Regions</h2>
              <p class="text-xs text-slate-500">Geographical regions.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-rose-50 text-rose-700">
              <i class="fa-solid fa-map"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used to group institutions and attendees.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/institutions" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Institutions</h2>
              <p class="text-xs text-slate-500">Universities and colleges.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-slate-50 text-slate-700">
              <i class="fa-solid fa-building-columns"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Attendees select these when registering.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/disabilities" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Disabilities</h2>
              <p class="text-xs text-slate-500">Disability options.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
              <i class="fa-solid fa-wheelchair"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used in health & accessibility information.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/dietaries" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Dietary Options</h2>
              <p class="text-xs text-slate-500">Dietary requirements.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
              <i class="fa-solid fa-utensils"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used when attendees specify dietary needs.</p>
        </Link>
      </Card>

      <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
        <Link href="/admin/settings/chronic-conditions" class="block space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Chronic Conditions</h2>
              <p class="text-xs text-slate-500">Health conditions.</p>
            </div>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-red-50 text-red-700">
              <i class="fa-solid fa-heart-pulse"></i>
            </span>
          </div>
          <p class="text-xs text-slate-500">Used in health and emergency planning.</p>
        </Link>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
</script>

<script>
export default {
  layout: Layout,
};
</script>
