<script setup>
import {
  onMounted,
  reactive,
  ref,
  inject,
  computed,
  nextTick,
  watch,
} from "vue";
import HealthRecordHeader from "../components/HealthRecordHeader.vue";
import HealthRecordRow from "../components/HealthRecordRow.vue";
import {
  fetchDiary,
  fetchAllHealthRecords,
  postHealthRecord,
  deleteHealthRecord,
} from "../api/api.js";
import { useTheme } from "vuetify";
import useErrorStack from "../composables/ErrorStack.js";
import _ from "lodash";
import useHealthRecordSettings from "../composables/HealthRecordSettings.js";

const { healthRecordSettings, setHealthRecordSettings } =
  useHealthRecordSettings();

const props = defineProps({
  diaryId: Number,
});

const emit = defineEmits(["close"]);

const { errorMessages, addErrorMessage } = useErrorStack();
const theme = useTheme();
const primaryColor = theme.current.value.colors.primary;
const secondaryColor = theme.current.value.colors.secondary;
const userCredentials = inject("userCredentials");
const isLoading = ref(true);
const selectedRow = ref(null);
const rows = reactive([]);

async function createAndEdit() {
  try {
    const data = [];
    const result = await postHealthRecord(userCredentials, props.diaryId, data);
    selectedRow.value = result.id;
  } catch (error) {
    addErrorMessage(
      "Failed to add new health record. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  await fetchRecords();
}

async function deleteRecord(id) {
  try {
    const data = [];
    const result = await deleteHealthRecord(userCredentials, id);
  } catch (error) {
    addErrorMessage(
      "Failed to add delete health record. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  await fetchRecords();
}

async function fetchRecords() {
  try {
    const data = await fetchAllHealthRecords(userCredentials, props.diaryId);

    rows.length = 0;
    rows.push(...data);
  } catch (error) {
    addErrorMessage(
      "Failed to load health record data. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

onMounted(async () => {
  try {
    const data = await fetchDiary(userCredentials, props.diaryId);
    setHealthRecordSettings(data);
  } catch (error) {
    addErrorMessage(
      "Failed to load health record table. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  await fetchRecords();

  isLoading.value = false;
});
</script>

<template>
  <template v-if="!isLoading">
    <health-record-header
      :diary-id="diaryId"
      :can-delete="rows.length == 0"
      @close="emit('close')"
      @add-health-record="createAndEdit"
    ></health-record-header>

    <v-sheet
      class="rounded-lg mt-10 pa-2 text-center mx-auto d-flex flex-column"
      elevation="4"
      max-width="800"
      width="100%"
    >
      <v-table hover>
        <thead>
          <tr>
            <th
              v-for="(column, i) in healthRecordSettings.columns"
              class="text-left"
              :key="i"
            >
              {{ column.title }}
            </th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in rows" :key="row.id" @click="selectedRow = row.id">
            <health-record-row
              :id="row.id"
              :selected-row="selectedRow"
              :columns="healthRecordSettings.columns"
              :data="row.data"
              @edit="(id) => selectedRow = id"
              @delete="deleteRecord"
            >
            </health-record-row>
          </tr>
        </tbody>
      </v-table>
    </v-sheet>
  </template>
</template>

<style>
.header-bg {
  /* background-image: var(--header-bg-image); */
  background-image: url(/assets/images/balti-ziedi.jpg);
  background-size: cover !important;
  /* background: linear-gradient(0deg, rgba(94, 90, 85, 0.2) 0%, rgb(201, 192, 181) 100%); */
}
</style>