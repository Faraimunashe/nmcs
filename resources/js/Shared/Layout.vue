<template>
    <div class="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-emerald-50 text-slate-900">
        <Header />

        <main class="mx-auto max-w-7xl px-4 py-6">
            <slot />
        </main>

        <Footer />
        <Flash />
    </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';
import Flash from './Flash.vue';

export default {
    components: {
        Header,
        Footer,
        Flash
    }
}
</script>
