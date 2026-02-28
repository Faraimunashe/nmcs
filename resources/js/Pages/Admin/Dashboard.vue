<template>
  <Head title="Admin Dashboard" />
  
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900">Admin Dashboard</h1>
      <p class="mt-1 text-sm text-slate-500">Overview of registrations, payments, and statistics</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Total Students</p>
          <p class="text-3xl font-semibold text-slate-900">{{ stats.total_students }}</p>
          <p class="text-xs text-slate-500">Registered attendants</p>
        </div>
      </Card>
      <Link href="/admin/attendants/payments/fully-paid" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">Fully Paid</p>
            <p class="text-3xl font-semibold text-emerald-700">{{ stats.fully_paid }}</p>
            <p class="text-xs text-slate-500">Complete payments</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
      <Link href="/admin/attendants/payments/partial" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">Partial Payment</p>
            <p class="text-3xl font-semibold text-amber-700">{{ stats.partial_payment }}</p>
            <p class="text-xs text-slate-500">Paid but not fully</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
      <Link href="/admin/attendants/payments/none" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">No Payment</p>
            <p class="text-3xl font-semibold text-red-700">{{ stats.no_payment }}</p>
            <p class="text-xs text-slate-500">Not yet paid</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <Link href="/admin/attendants/health/dietary" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">Dietary Issues</p>
            <p class="text-3xl font-semibold text-blue-700">{{ stats.dietary_issues }}</p>
            <p class="text-xs text-slate-500">Students with dietary requirements</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
      <Link href="/admin/attendants/health/chronic" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">Chronic Conditions</p>
            <p class="text-3xl font-semibold text-orange-700">{{ stats.chronic_conditions }}</p>
            <p class="text-xs text-slate-500">Students with chronic conditions</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
      <Link href="/admin/attendants/health/disabilities" class="block">
        <Card class="cursor-pointer hover:ring-2 hover:ring-emerald-400/40 transition">
          <div class="space-y-2">
            <p class="text-xs font-semibold text-slate-600">Disabilities</p>
            <p class="text-3xl font-semibold text-purple-700">{{ stats.disabilities }}</p>
            <p class="text-xs text-slate-500">Students with disabilities</p>
            <p class="text-xs text-emerald-700 mt-2 font-semibold">Click to view list →</p>
          </div>
        </Card>
      </Link>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Students by Gender</h2>
            <p class="text-xs text-slate-500">Male vs Female distribution</p>
          </div>
          <div class="h-64">
            <canvas ref="genderChart"></canvas>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Students by Region</h2>
            <p class="text-xs text-slate-500">Regional distribution</p>
          </div>
          <div class="h-64">
            <canvas ref="regionChart"></canvas>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Top Institutions</h2>
            <p class="text-xs text-slate-500">Students by institution (Top 10)</p>
          </div>
          <div class="h-64">
            <canvas ref="institutionChart"></canvas>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Payment Status</h2>
            <p class="text-xs text-slate-500">Fully paid, partial, or no payment</p>
          </div>
          <div class="h-64">
            <canvas ref="paymentStatusChart"></canvas>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Health Issues</h2>
            <p class="text-xs text-slate-500">Dietary, chronic conditions, and disabilities</p>
          </div>
          <div class="h-64">
            <canvas ref="healthIssuesChart"></canvas>
          </div>
        </div>
      </Card>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
      <Card>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Recent Registrations</h2>
              <p class="text-xs text-slate-500">Latest student registrations</p>
            </div>
            <Link href="/admin/attendants" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
              View all
            </Link>
          </div>
          <div v-if="recentRegistrations && recentRegistrations.length > 0" class="divide-y divide-slate-100">
            <div
              v-for="student in recentRegistrations"
              :key="student.id"
              class="px-4 py-3 flex items-start justify-between gap-4"
            >
              <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-900">{{ student.name }}</p>
                <p class="text-xs text-slate-500">{{ student.email }}</p>
                <p class="text-xs text-slate-500">{{ student.institution }} • {{ student.region }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ student.registered_at }}</p>
              </div>
            </div>
          </div>
          <EmptyState v-else icon="fas fa-users" title="No registrations" description="No students registered yet" />
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-slate-900">Pending Payments</h2>
              <p class="text-xs text-slate-500">Awaiting approval</p>
            </div>
            <Link href="/admin/payments" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
              View all
            </Link>
          </div>
          <div v-if="pendingPayments && pendingPayments.length > 0" class="divide-y divide-slate-100">
            <div
              v-for="payment in pendingPayments"
              :key="payment.id"
              class="px-4 py-3 flex items-start justify-between gap-4"
            >
              <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-900">{{ payment.student_name }}</p>
                <p class="text-xs text-slate-500">{{ payment.purpose }} • {{ payment.method }}</p>
                <p class="text-xs text-slate-500">Ref: {{ payment.reference || 'N/A' }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ payment.date }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-semibold text-slate-900">${{ payment.amount }}</p>
                <p class="text-xs text-slate-500">Final: ${{ payment.final_amount }}</p>
              </div>
            </div>
          </div>
          <EmptyState v-else icon="fas fa-money-bill-wave" title="No pending payments" description="All payments are processed" />
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import EmptyState from '../../Shared/Components/EmptyState.vue';
import { ref, onMounted, nextTick } from 'vue';
import {
  Chart,
  ArcElement,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  DoughnutController,
  PieController,
  BarController
} from 'chart.js';

Chart.register(
  ArcElement,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  DoughnutController,
  PieController,
  BarController
);

const props = defineProps({
  stats: Object,
  charts: Object,
  recentRegistrations: Array,
  pendingPayments: Array
});

const genderChart = ref(null);
const regionChart = ref(null);
const institutionChart = ref(null);
const paymentStatusChart = ref(null);
const healthIssuesChart = ref(null);

let chartInstances = [];

const createChart = (canvas, type, data, options = {}) => {
  if (!canvas) return null;
  
  const chart = new Chart(canvas, {
    type,
    data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
      },
      ...options
    }
  });
  
  chartInstances.push(chart);
  return chart;
};

onMounted(() => {
  nextTick(() => {
    if (props.charts.students_by_gender && genderChart.value && props.charts.students_by_gender.length > 0) {
      createChart(genderChart.value, 'doughnut', {
        labels: props.charts.students_by_gender.map(item => item.label),
        datasets: [{
          data: props.charts.students_by_gender.map(item => item.value),
          backgroundColor: ['#3b82f6', '#ec4899'],
        }]
      });
    }

    if (props.charts.students_by_region && regionChart.value && props.charts.students_by_region.length > 0) {
      createChart(regionChart.value, 'bar', {
        labels: props.charts.students_by_region.map(item => item.label),
        datasets: [{
          label: 'Students',
          data: props.charts.students_by_region.map(item => item.value),
          backgroundColor: '#10b981',
        }]
      }, {
        indexAxis: 'y',
      });
    }

    if (props.charts.students_by_institution && institutionChart.value && props.charts.students_by_institution.length > 0) {
      createChart(institutionChart.value, 'bar', {
        labels: props.charts.students_by_institution.map(item => item.label),
        datasets: [{
          label: 'Students',
          data: props.charts.students_by_institution.map(item => item.value),
          backgroundColor: '#10b981',
        }]
      }, {
        indexAxis: 'y',
      });
    }

    if (props.charts.payment_status && paymentStatusChart.value && props.charts.payment_status.length > 0) {
      createChart(paymentStatusChart.value, 'doughnut', {
        labels: props.charts.payment_status.map(item => item.label),
        datasets: [{
          data: props.charts.payment_status.map(item => item.value),
          backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
        }]
      });
    }

    if (props.charts.health_issues && healthIssuesChart.value && props.charts.health_issues.length > 0) {
      createChart(healthIssuesChart.value, 'bar', {
        labels: props.charts.health_issues.map(item => item.label),
        datasets: [{
          label: 'Students',
          data: props.charts.health_issues.map(item => item.value),
          backgroundColor: ['#3b82f6', '#f97316', '#a855f7'],
        }]
      });
    }
  });
});
</script>

<script>
export default {
  layout: Layout
};
</script>
