<script setup>
import { onMounted, reactive, watch, inject } from "vue";
import { useTheme } from "vuetify";
import { putHealthRecord } from "../api/api.js";
import useErrorStack from "../composables/ErrorStack.js";
import _ from "lodash";
import DateInput from "../components/DateInput.vue";
import TimeInput from "../components/TimeInput.vue";
import DateTimeInput from "../components/DateTimeInput.vue";

const theme = useTheme();

const emit = defineEmits(["edit", "delete", "is-editting"]);

const props = defineProps({
  id: Number,
  selectedRow: Number,
  columns: Array,
  data: Object,
});

const { errorMessages, addErrorMessage } = useErrorStack();
const userCredentials = inject("userCredentials");
const primaryColor = theme.current.value.colors.primary;
const localData = reactive({});

async function updateRecord() {
  try {
    await putHealthRecord(userCredentials, props.id, localData);
  } catch (e) {
    addErrorMessage(
      "Failed to update health record. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

const handleUpdateDebounced = _.debounce(updateRecord, 1);

watch(
  () => props.data,
  () => {
    createLocalData();
  },
  { deep: true }
);

watch(
  () => localData,
  () => {
    handleUpdateDebounced();
  },
  { deep: true }
);

function createLocalData() {
  for (const column of props.columns) {
    if (!Object.hasOwn(localData, column.identifier)) {
      localData[column.identifier] = props.data[column.identifier] ?? "";
    }
  }
}

function emitIsEditting(isOpen) {
  emit("is-editting", isOpen);
}

onMounted(() => {
  createLocalData();
});
</script>

<template>
  <template v-if="selectedRow != id">
    <td
      v-for="(column, i) in columns"
      class="text-left"
      :key="id + '-' + column.identifier"
    >
      {{ localData[column.identifier] ?? "" }}
    </td>
    <td class="pa-0">
      <div class="d-flex justify-end">
        <v-btn
          variant="text"
          rounded="xl"
          class="min-w"
          @click="emit('edit', id)"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="18"
            width="18"
            viewBox="0 0 512 512"
          >
            <path
              :fill="primaryColor"
              d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"
            />
          </svg>
        </v-btn>
      </div>
    </td>
  </template>
  <template v-else>
    <td v-for="(column, i) in columns" class="text-left" :key="i">
      <v-text-field
        v-if="column.type == 'Text'"
        v-model="localData[column.identifier]"
        :label="column.title"
        :hide-details="true"
        variant="plain"
      ></v-text-field>

      <date-input
        :id="id + column.identifier"
        v-else-if="column.type == 'Date'"
        v-model="localData[column.identifier]"
        @is-open="emitIsEditting"
      ></date-input>

      <time-input
        :id="id + column.identifier"
        v-else-if="column.type == 'Time'"
        v-model="localData[column.identifier]"
        @is-open="emitIsEditting"
      ></time-input>

      <date-time-input
        :id="id + column.identifier"
        v-else-if="column.type == 'Date and time'"
        v-model="localData[column.identifier]"
        @is-open="emitIsEditting"
      ></date-time-input>
    </td>
    <td class="pa-0">
      <div class="d-flex justify-end">
        <v-btn
          variant="text"
          rounded="xl"
          class="min-w"
          @click="emit('delete', id)"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="18"
            width="18"
            viewBox="0 0 512 512"
          >
            <path
              :fill="primaryColor"
              d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
            />
          </svg>
        </v-btn>
      </div>
    </td>
  </template>
</template>