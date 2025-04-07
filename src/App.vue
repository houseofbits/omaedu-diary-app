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
import { fetchUser } from "./api/api.js";
import DiaryView from "./views/DiaryView.vue";
import HealthRecordView from "./views/HealthRecordView.vue";

import AppHeader from "./components/AppHeader.vue";

import useErrorStack from "./composables/ErrorStack.js";
import useSettings from "./composables/Settings.js";
import { useTheme } from "vuetify";
import { fetchAllDiaries } from "./api/api";

const {
  settings,
  setSettings,
  getPageBackgroundImageUrl,
  getDiaryBackgroundImageUrl,
} = useSettings();
const { errorMessages, addErrorMessage } = useErrorStack();

const theme = useTheme();
const userCredentials = inject("userCredentials");

let user = ref(null);
const isErrorVisible = ref(true);

const primaryColor = theme.current.value.colors.primary;
const backgroundColor = theme.current.value.colors.background;

const diaries = reactive([]);
const selectedDiaryId = ref(null);
const selectedDiaryType = ref("diary");
const isSelectedDiaryLoading = ref(false);

const cssThemeProps = computed(() => {
  return {
    "--header-bg-image": "url(" + getPageBackgroundImageUrl() + ")",
  };
});

function calculateSnackbarMargin(i) {
  return i * 60 + "px";
}

const isDiarySelected = computed(() => {
  return selectedDiaryId.value != null && selectedDiaryType.value == "diary";
});

const isHealthRecordSelected = computed(() => {
  return (
    selectedDiaryId.value != null && selectedDiaryType.value == "health-record"
  );
});

const isMainSectionVisible = computed(() => {
  return (
    selectedDiaryId.value == null ||
    (selectedDiaryId.value != null && isSelectedDiaryLoading.value == true)
  );
});

function selectDiary(id, type) {
  if (selectedDiaryId.value != id) {
    selectedDiaryId.value = id;
    selectedDiaryType.value = type;
    isSelectedDiaryLoading.value = true;
  }
}

async function fetchDiaries() {
  try {
    const diariesData = await fetchAllDiaries(userCredentials);

    diaries.length = 0;
    diaries.push(...diariesData);
  } catch (e) {
    addErrorMessage(
      "Failed to load diaries. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

function closeSelectedView() {
  selectedDiaryId.value = null;
  fetchDiaries();
}

async function getUserData() {
  try {
    const userData = await fetchUser(userCredentials);
    user.value = userData;
    setSettings(user.value.settings);
  } catch (error) {
    addErrorMessage(
      "Failed to load user data. Try to refresh the page and if the problem persists please contact the technical support."
    );
  }
}

function onDiaryCreated(diaryId) {
  selectDiary(diaryId, "diary");
}

function onHealthRecordTableCreated(diaryId) {
  selectDiary(diaryId, "health-record");
}

function isDiaryLoading(id) {
  return selectedDiaryId.value == id && isSelectedDiaryLoading.value;
}

onMounted(async () => {
  await getUserData();
  await fetchDiaries();
});
</script>

<template>
  <v-app v-if="user" :style="cssThemeProps">
    <diary-view
      v-if="isDiarySelected"
      :diary-id="selectedDiaryId"
      @close="closeSelectedView"
      @loaded="isSelectedDiaryLoading = false"
    ></diary-view>

    <health-record-view
      v-else-if="isHealthRecordSelected"
      :diary-id="selectedDiaryId"
      @close="closeSelectedView"
      @loaded="isSelectedDiaryLoading = false"
    ></health-record-view>

    <template v-if="isMainSectionVisible">
      <app-header
        @diary-created="onDiaryCreated"
        @health-record-created="onHealthRecordTableCreated"
      />

      <v-container class="pt-2" max-width="1100" min-width="400">
        <v-row dense>
          <v-col v-for="(diary, i) in diaries" :key="i" cols="12" md="4">
            <v-card
              :loading="isDiaryLoading(diary.id)"
              variant="elevated"
              class="mx-auto"
              :color="diary.color"
              max-width="344"
              link
              @click="selectDiary(diary.id, diary.type)"
            >
              <v-card-item>
                <v-card-title>
                  <div class="d-flex justify-space-between align-center">
                    <div class="text-truncate">{{ diary.title }}</div>

                    <svg
                      v-if="diary.type == 'diary'"
                      xmlns="http://www.w3.org/2000/svg"
                      height="20"
                      width="20"
                      viewBox="0 0 448 512"
                      class="card-icon"
                    >
                      >
                      <path
                        :fill="primaryColor"
                        d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"
                      />
                    </svg>
                    <svg
                      v-else
                      xmlns="http://www.w3.org/2000/svg"
                      height="20"
                      width="20"
                      viewBox="0 0 512 512"
                      class="card-icon"
                    >
                      <path
                        :fill="primaryColor"
                        d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l80 0 0 56-80 0 0-56zm0 104l80 0 0 64-80 0 0-64zm128 0l96 0 0 64-96 0 0-64zm144 0l80 0 0 64-80 0 0-64zm80-48l-80 0 0-56 80 0 0 56zm0 160l0 40c0 8.8-7.2 16-16 16l-64 0 0-56 80 0zm-128 0l0 56-96 0 0-56 96 0zm-144 0l0 56-64 0c-8.8 0-16-7.2-16-16l0-40 80 0zM272 248l-96 0 0-56 96 0 0 56z"
                      />
                    </svg>
                  </div>
                </v-card-title>

                <v-card-subtitle>
                  {{ diary.type == "diary" ? "Diary" : "Health record" }}
                </v-card-subtitle>
              </v-card-item>

              <v-card-text>{{ diary.description }}</v-card-text>
            </v-card>

            <div class="text-center text-caption"></div>
          </v-col>
        </v-row>
      </v-container>
    </template>
  </v-app>

  <v-snackbar
    v-for="(message, i) in errorMessages"
    :style="{ 'margin-bottom': calculateSnackbarMargin(i) }"
    :key="i"
    v-model="isErrorVisible"
    :timeout="-1"
    color="red-darken-3"
  >
    {{ message.text }}
  </v-snackbar>
</template>

<style>
.card-icon {
  flex-basis: 20px;
  flex-grow: 0;
  flex-shrink: 0;
}
</style>