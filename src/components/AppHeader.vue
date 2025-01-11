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
import InformationModal from "./Modals/InformationModal.vue";
import DiarySettingsModal from "./Modals/DiarySettingsModal.vue";
import HealthRecordSetingsModal from "./Modals/HealthRecordSetingsModal.vue";

import { useTheme } from "vuetify";

const theme = useTheme();

const emit = defineEmits(["diary-created"]);

const primaryColor = theme.current.value.colors.primary;
const backgroundColor = theme.current.value.colors.background;

const isInfoModalOpen = ref(false);
const isDiarySettingsModalOpen = ref(false);
const isHealthRecordSettingsModalOpen = ref(false);
</script>

<template>
  <information-modal v-model="isInfoModalOpen" />

  <diary-settings-modal
    v-model="isDiarySettingsModalOpen"
    @created="(diaryId) => emit('diary-created', diaryId)"
  ></diary-settings-modal>

  <health-record-setings-modal
    v-model="isHealthRecordSettingsModalOpen"
    @created="(diaryId) => emit('health-record-created', diaryId)"
  ></health-record-setings-modal>

  <v-container fluid class="header-bg mb-2">
    <v-row justify="center" class="mb-2">
      <v-col cols="2">
        <v-btn
          color="primary"
          size="45"
          rounded="xl"
          class="mr-2 mb-2 min-w"
          @click="isInfoModalOpen = true"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="25"
            width="12"
            viewBox="0 0 192 512"
          >
            <path
              :fill="backgroundColor"
              d="M48 80a48 48 0 1 1 96 0A48 48 0 1 1 48 80zM0 224c0-17.7 14.3-32 32-32l64 0c17.7 0 32 14.3 32 32l0 224 32 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 512c-17.7 0-32-14.3-32-32s14.3-32 32-32l32 0 0-192-32 0c-17.7 0-32-14.3-32-32z"
            />
          </svg>
        </v-btn>
      </v-col>
      <v-col class="d-flex justify-center text-h4"> Application name </v-col>
      <v-col cols="2"></v-col>
    </v-row>
    <v-row no-gutters justify="center">
      <v-col class="d-flex justify-center">
        <v-btn class="mx-2" @click="isDiarySettingsModalOpen = true">
          <template v-slot:prepend>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="20"
              width="20"
              viewBox="0 0 512 512"
              class="my-2"
            >
              <path
                :fill="primaryColor"
                d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"
              />
            </svg>
          </template>
          Start a new diary
        </v-btn>

        <v-btn class="mx-2" @click="isHealthRecordSettingsModalOpen = true">
          <template v-slot:prepend>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="20"
              width="17.5"
              viewBox="0 0 448 512"
            >
              <path
                :fill="primaryColor"
                d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l80 0 0 56-80 0 0-56zm0 104l80 0 0 64-80 0 0-64zm128 0l96 0 0 64-96 0 0-64zm144 0l80 0 0 64-80 0 0-64zm80-48l-80 0 0-56 80 0 0 56zm0 160l0 40c0 8.8-7.2 16-16 16l-64 0 0-56 80 0zm-128 0l0 56-96 0 0-56 96 0zm-144 0l0 56-64 0c-8.8 0-16-7.2-16-16l0-40 80 0zM272 248l-96 0 0-56 96 0 0 56z"
              />
            </svg>
          </template>
          Add health record table
        </v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>