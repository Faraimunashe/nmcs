<template>
    <header v-if="user" class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200">
      <div class="mx-auto max-w-7xl px-4 py-3">
        <div class="flex items-center justify-between gap-3">
          <div class="flex items-center gap-3 min-w-0">
            <Link href="/dashboard" class="flex items-center gap-3 min-w-0">
              <div class="h-10 w-10 rounded-2xl bg-white ring-1 ring-emerald-100 shadow-sm overflow-hidden">
                <img src="./../../images/nmcs.jpeg" alt="NMCS" class="h-full w-full object-contain p-2" />
              </div>
              <div class="min-w-0 leading-tight">
                <p class="text-sm font-semibold tracking-tight text-emerald-700 truncate">NMCS Zimbabwe</p>
                <p class="text-xs text-slate-500 truncate">Easter Conference System</p>
              </div>
            </Link>
          </div>

          <div class="flex items-center gap-2">
            <nav class="hidden md:flex items-center gap-2">
              <template v-if="isAdmin">
                <Link
                  href="/admin/dashboard"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/admin/dashboard') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-chart-line"></i>
                  <span class="hidden lg:inline">Dashboard</span>
                </Link>

                <Link
                  href="/admin/attendants"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/admin/attendants') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-users"></i>
                  <span class="hidden lg:inline">Attendants</span>
                </Link>

                <Link
                  href="/admin/payments"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/admin/payments') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-money-bill-wave"></i>
                  <span class="hidden lg:inline">Payments</span>
                </Link>
                <Link
                  href="/admin/users"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/admin/users') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-user-shield"></i>
                  <span class="hidden lg:inline">Users</span>
                </Link>

                <div class="relative" ref="settingsMenuRef">
                  <button
                    type="button"
                    @click.stop="toggleSettingsMenu"
                    :class="[
                      'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                      $page.url.startsWith('/admin/settings')
                        ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'
                        : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                    ]"
                  >
                    <i class="fa-solid fa-gear"></i>
                    <span class="hidden lg:inline">Settings</span>
                    <i class="fa-solid fa-chevron-down text-[11px]" :class="{ 'rotate-180': showSettingsMenu }"></i>
                  </button>
                  <Transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                  >
                    <div
                      v-if="showSettingsMenu"
                      class="absolute right-0 mt-2 w-64 rounded-2xl bg-white ring-1 ring-slate-200 shadow-lg py-2 z-40"
                    >
                      <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Settings</p>
                      </div>
                      <div class="py-1 text-sm">
                        <Link
                          href="/admin/settings/conference-fees"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-receipt w-4"></i>
                          Conference Fees
                        </Link>
                        <Link
                          href="/admin/settings/payment-charges"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-percent w-4"></i>
                          Payment Charges
                        </Link>
                        <Link
                          href="/admin/settings/payment-methods"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-money-bill-wave w-4"></i>
                          Payment Methods
                        </Link>
                        <Link
                          href="/admin/settings/payment-recipients"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-user-tag w-4"></i>
                          Payment Recipients
                        </Link>
                        <Link
                          href="/admin/settings/memberships"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-id-card w-4"></i>
                          Memberships
                        </Link>
                        <Link
                          href="/admin/settings/regions"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-map w-4"></i>
                          Regions
                        </Link>
                        <Link
                          href="/admin/settings/institutions"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-building-columns w-4"></i>
                          Institutions
                        </Link>
                        <Link
                          href="/admin/settings/disabilities"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-wheelchair w-4"></i>
                          Disabilities
                        </Link>
                        <Link
                          href="/admin/settings/dietaries"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-utensils w-4"></i>
                          Dietary Options
                        </Link>
                        <Link
                          href="/admin/settings/chronic-conditions"
                          class="flex items-center gap-3 px-4 py-2 text-slate-700 hover:bg-slate-50"
                          @click="closeSettingsMenu"
                        >
                          <i class="fa-solid fa-heart-pulse w-4"></i>
                          Chronic Conditions
                        </Link>
                      </div>
                    </div>
                  </Transition>
                </div>
              </template>
              <template v-else>
                <Link
                  href="/dashboard"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/dashboard') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-house"></i>
                  <span class="hidden lg:inline">Dashboard</span>
                </Link>

                <Link
                  href="/attendants"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/attendants') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-users"></i>
                  <span class="hidden lg:inline">Attendants</span>
                </Link>

                <Link
                  href="/payments"
                  :class="[
                    'inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold transition',
                    $page.url.startsWith('/payments') 
                      ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' 
                      : 'text-slate-700 hover:bg-slate-50 ring-1 ring-slate-200 hover:ring-emerald-200'
                  ]"
                >
                  <i class="fa-solid fa-money-bill-wave"></i>
                  <span class="hidden lg:inline">Payments</span>
                </Link>
              </template>
            </nav>

            <div class="relative" ref="userMenuRef">
              <button
                type="button"
                @click.stop="toggleUserMenu"
                class="flex items-center gap-2 h-10 px-3 rounded-2xl bg-white ring-1 ring-slate-200 hover:ring-emerald-200 transition"
                aria-label="User menu"
              >
                <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center">
                  <i class="fa-solid fa-circle-user text-emerald-700 text-lg"></i>
                </div>
                <span class="hidden sm:inline text-sm font-semibold text-slate-700">{{ user?.name || 'User' }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-slate-500" :class="{ 'rotate-180': showUserMenu }"></i>
              </button>

              <Transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div
                  v-if="showUserMenu"
                  class="absolute right-0 mt-2 w-56 rounded-2xl bg-white ring-1 ring-slate-200 shadow-lg py-2 z-50"
                >
                <div class="px-4 py-3 border-b border-slate-100">
                  <p class="text-sm font-semibold text-slate-900">{{ user?.name || 'User' }}</p>
                  <p class="text-xs text-slate-500 truncate">{{ user?.email || '' }}</p>
                </div>
                <div class="py-1">
                  <template v-if="isAdmin">
                    <Link
                      href="/admin/dashboard"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-chart-line w-4"></i>
                      Admin Dashboard
                    </Link>
                    <Link
                      href="/admin/attendants"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-users w-4"></i>
                      All Attendants
                    </Link>
                    <Link
                      href="/admin/payments"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-money-bill-wave w-4"></i>
                      All Payments
                    </Link>
                    <Link
                      href="/admin/users"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-user-shield w-4"></i>
                      Users
                    </Link>
                    <Link
                      href="/admin/settings"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-gear w-4"></i>
                      Settings
                    </Link>
                  </template>
                  <template v-else>
                    <Link
                      href="/dashboard"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-house w-4"></i>
                      Dashboard
                    </Link>
                    <Link
                      href="/attendants"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-users w-4"></i>
                      My Attendants
                    </Link>
                    <Link
                      href="/payments"
                      class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                      @click="closeUserMenu"
                    >
                      <i class="fa-solid fa-money-bill-wave w-4"></i>
                      My Payments
                    </Link>
                  </template>
                </div>
                <div class="border-t border-slate-100 pt-1">
                  <form @submit.prevent="logout">
                    <button
                      type="submit"
                      class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                    >
                      <i class="fa-solid fa-right-from-bracket w-4"></i>
                      Logout
                    </button>
                  </form>
                </div>
                </div>
              </Transition>
            </div>
          </div>
        </div>
      </div>
    </header>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

const page = usePage();
const showUserMenu = ref(false);
const userMenuRef = ref(null);
const showSettingsMenu = ref(false);
const settingsMenuRef = ref(null);

const user = computed(() => page.props.auth?.user || page.props.user);

const isAdmin = computed(() => {
  if (!user.value) return false;
  return page.props.auth?.user?.roles?.some(role => role.name === 'admin') || false;
});

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const closeUserMenu = () => {
  showUserMenu.value = false;
};

const toggleSettingsMenu = () => {
  showSettingsMenu.value = !showSettingsMenu.value;
};

const closeSettingsMenu = () => {
  showSettingsMenu.value = false;
};

const logout = () => {
  router.post('/logout');
};

const handleClickOutside = (event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    closeUserMenu();
  }
  if (settingsMenuRef.value && !settingsMenuRef.value.contains(event.target)) {
    closeSettingsMenu();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
