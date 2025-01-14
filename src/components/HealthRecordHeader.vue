<script setup>
import {
  reactive,
  ref,
  nextTick,
  onMounted,
  getCurrentInstance,
  watch,
  inject,
} from "vue";
import { useTheme } from "vuetify";
import HealthRecordSettingsModal from "./Modals/HealthRecordSetingsModal.vue";
import { deleteDiary } from "../api/api.js";
import useErrorStack from "../composables/ErrorStack.js";
import useHealthRecordSettings from "../composables/HealthRecordSettings.js";

const userCredentials = inject("userCredentials");
const { addErrorMessage } = useErrorStack();
const theme = useTheme();
const emit = defineEmits(["add-health-record", "download", "print", "close"]);
const props = defineProps({
  diaryId: Number,
  canDelete: Boolean,
});

const { healthRecordSettings, setHealthRecordSettings } =
  useHealthRecordSettings();

const primaryColor = theme.current.value.colors.primary;
const backgroundColor = theme.current.value.colors.background;
const isSettingsModalOpen = ref(false);
const isDeleteConfirmationModalOpen = ref(false);

function addAndEdit() {
  emit("add-health-record");
}

async function confirmDeletion() {
  try {
    const data = await deleteDiary(userCredentials, props.diaryId);
    emit("close");
  } catch (error) {
    addErrorMessage(
      "Failed to delete health record table. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }

  isDeleteConfirmationModalOpen.value = false;
}
</script>

<template>
  <health-record-settings-modal
    v-model="isSettingsModalOpen"
    :diary-id="diaryId"
  ></health-record-settings-modal>

  <v-container fluid class="header-bg pb-8" min-width="400">
    <v-row no-gutters justify="center">
      <v-col class="v-col-6 v-col-md-2 d-flex justify-start order-1">
        <v-btn @click="emit('close')" variant="text">
          <template v-slot:prepend>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="25"
              width="20"
              viewBox="0 0 448 512"
              class="ml-3 cursor-pointer"
            >
              <path
                :fill="primaryColor"
                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
              />
            </svg>
          </template>
          Back
        </v-btn>
      </v-col>

      <v-col
        class="d-flex justify-center text-h4 text-center v-col-12 v-col-md-8 order-3"
      >
        {{ healthRecordSettings.title }}
      </v-col>

      <v-col class="v-col-6 v-col-md-2 d-flex justify-end order-2 order-md-4">
        <v-btn
          @click="isSettingsModalOpen = true"
          variant="text"
          rounded="xl"
          class="min-w"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="20"
            width="20"
            viewBox="0 0 512 512"
          >
            <path
              :fill="primaryColor"
              d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"
            />
          </svg>
        </v-btn>
        <v-btn
          @click="isDeleteConfirmationModalOpen = true"
          variant="text"
          rounded="xl"
          class="min-w"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="25"
            width="20"
            viewBox="0 0 448 512"
          >
            <path
              :fill="primaryColor"
              d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
            />
          </svg>
        </v-btn>
      </v-col>
    </v-row>

    <v-fab
      extended
      location="bottom end"
      absolute
      offset
      color="primary"
      class="new-chapter-button"
      @click="addAndEdit"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="20"
        width="17.5"
        viewBox="0 0 448 512"
        class="mr-2"
      >
        <path
          fill="#ffffff"
          d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"
        />
      </svg>
      <span>NEW HEALTH RECORD</span>
    </v-fab>
  </v-container>

  <v-divider :thickness="1" class="border-opacity-75"></v-divider>

  <v-dialog v-model="isDeleteConfirmationModalOpen" width="auto">
    <v-card max-width="400" title="Delete health record table?">
      <v-card-text v-if="canDelete">
        Please note: this action cannot be undone!
      </v-card-text>
      <v-card-text v-else
        >To delete table, first remove all of its records!
      </v-card-text>

      <template v-slot:actions>
        <v-spacer></v-spacer>
        <v-btn
          class="ms-auto"
          text="Cancel"
          @click="isDeleteConfirmationModalOpen = false"
        ></v-btn>
        <v-btn
          v-if="canDelete"
          class="ms-auto"
          text="Delete"
          @click="confirmDeletion"
        ></v-btn>
      </template>
    </v-card>
  </v-dialog>
</template>

<style>
.diary-header-centered-text input {
  text-align: center;
  font-size: 2.125rem;
}
.new-chapter-button {
  bottom: -30px;
}
</style>