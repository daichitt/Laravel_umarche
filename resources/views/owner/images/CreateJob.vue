<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create job
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-10">
          <div class="max-w-md grid grid-cols-1 gap-6">
            <div class="col-span-6 sm:col-span-4">
              <jet-label for="client_name" value="Client name"/>
              <jet-input v-model="form.client_name" id="client_name" type="text" class="mt-1 block w-full" ref="client_name"/>
              <jet-input-error :message="form.errors.client_name" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
              <jet-label for="car" value="Car"/>
              <jet-input v-model="form.car" id="car" type="text" class="mt-1 block w-full" ref="car"/>
              <jet-input-error :message="form.errors.car" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
              <jet-label for="description" value="Description"/>
              <textarea v-model="form.description" id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" rows="5" ref="description"></textarea>
              <jet-input-error :message="form.errors.description" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
              <jet-label for="mechanic_id" value="Mechanic"/>
              <multiselect v-model="selectedMechanic" :options="mechanics" @search-change="onSearchMechanicsChange" @input="onSelectedMechanic" label="name" track-by="id" id="mechanic_id" placeholder="Search mechanic"></multiselect>
              <jet-input-error :message="form.errors.mechanic_id" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
              <jet-label for="consultant_id" value="Consultant"/>
              <multiselect v-model="selectedConsultant" :options="consultants" @search-change="onSearchConsultantsChange" @input="onSelectedConsultant" label="name" track-by="id" id="consultant_id" placeholder="Search consultant"></multiselect>
              <jet-input-error :message="form.errors.consultant_id" class="mt-2"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import Multiselect from 'vue-multiselect';
import {throttle} from "lodash";

export default {
  props: {
    mechanics: {
      type: Array,
      default: () => []
    },
    consultants: {
      type: Array,
      default: () => []
    }
  },
  components: {
    AppLayout,
    JetLabel,
    JetInput,
    JetInputError,
    Multiselect
  },
  data() {
    return {
      selectedMechanic: undefined,
      selectedConsultant: undefined,
      form: this.$inertia.form({
        client_name: '',
        car: '',
        description: '',
        mechanic_id: '',
        consultant_id: ''
      }),
    }
  },
  methods: {
    onSearchMechanicsChange: throttle(function (term) {
      this.$inertia.get('/jobs/create', {term}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
      })
    }, 300),
    onSelectedMechanic(consultant) {
      this.form.consultant_id = consultant.id;
    },
    onSearchConsultantsChange: throttle(function (term) {
      this.$inertia.get('/jobs/create', {term}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
      })
    }, 300),
    onSelectedConsultant(consultant) {
      this.form.consultant_id = consultant.id;
    }
  }
}
</script>